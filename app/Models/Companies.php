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
           'EMPRESAS_USUARIAS.EMPRESA_USUARIA',
           'PESSOAS_JURIDICAS.INSCRICAO_FEDERAL',
           'EMPRESAS_USUARIAS.NOME',
    )->Join('EMPRESAS_USUARIAS', function ($join) {
        $join->on('LG_IMPORTA_FUNCIONARIOS.ESTABELECIMENTO', '=', 'EMPRESAS_USUARIAS.EMPRESA_USUARIA');
    })->Join('PESSOAS_JURIDICAS', function ($join) {
         $join->on('EMPRESAS_USUARIAS.ENTIDADE', '=', 'PESSOAS_JURIDICAS.ENTIDADE')
         ->where('PESSOAS_JURIDICAS.CADASTRO_ATIVO', 'S');
    })
   ->distinct()->get();
}


    public static function getAllCompaniesRegistered(){

            return RegisteredCompanies::all();


    }
   



}
