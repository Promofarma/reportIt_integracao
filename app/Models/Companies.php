<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Models\RegisteredCompanies;

class Companies extends Model
{
    use HasFactory;

    protected $connection = 'sqlsrv';

    protected $table = 'LG_IMPORTA_FUNCIONARIOS';

    public $timestamps = false;


    public function getCompaniesToRegister()
    {
        $AllCompaniesRegistered = self::getAllCompaniesRegistered();
        $AllCompanies = self::getAllCompanies();
        return $AllCompanies->whereNotIn('INSCRICAO_FEDERAL', $AllCompaniesRegistered->pluck('DOCUMENT'));
    }

    public static function getAllCompanies()
    {
        return self::query()
            ->select(
                
                'PESSOAS_JURIDICAS.INSCRICAO_FEDERAL',
                DB::RAW("REPLACE(PESSOAS_JURIDICAS.nome, 'ORGANIZACAO FARMACEUTICA NAKANO LTDA', 'ORGANIZAÇÃO FARMACEUTICA NAKANO LTDA') AS NOME"),
            )->Join('CENTROS_CUSTO', function ($join) {
                $join->on('LG_IMPORTA_FUNCIONARIOS.CENTRO_CUSTO', '=', 'CENTROS_CUSTO.OBJETO_CONTROLE');
            })->Join('EMPRESAS_USUARIAS', function ($join) {
                $join->on('EMPRESAS_USUARIAS.EMPRESA_USUARIA', '=', 'CENTROS_CUSTO.EMPRESA_USUARIA');
            })->Join('PESSOAS_JURIDICAS', function ($join) {
                $join->on('PESSOAS_JURIDICAS.ENTIDADE', '=', 'EMPRESAS_USUARIAS.ENTIDADE')
                ->where('PESSOAS_JURIDICAS.CADASTRO_ATIVO', 'S');
            })
            ->distinct()
            ->orderBy('PESSOAS_JURIDICAS.INSCRICAO_FEDERAL', 'ASC')
            ->get();
    }
    public static function getAllCompaniesRegistered()
    {
        return RegisteredCompanies::all();
    }
}
