<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Models\RegisteredDepartments;

class Departments extends Model
{
    use HasFactory;

    protected $connection = 'sqlsrv';

    protected $table = 'CENTROS_CUSTO';

    protected $primaryKey = 'OBJETO_CONTROLE';


    public $timestamps = false;


    public function getDepartaments()
    {
        $registeredDepartments = $this->getDepartamentsRegister();
        $allDepartaments = $this->getDepartamentsAll();

        return $allDepartaments->whereNotIn('OBJETO_CONTROLE', $registeredDepartments);

      
    }


 public static function getDepartamentsAll()
{
    return static::query()
        ->select(
            'CENTROS_CUSTO.OBJETO_CONTROLE',
            DB::raw("
                CASE 
                    WHEN LOJAS.LOJA_FISICA = 'S' 
                     AND LOJAS.ATIVA = 'S' 
                    THEN LOJAS.NOME_RESUMIDO
                    ELSE CENTROS_CUSTO.DESCRICAO
                END AS DESCRICAO
            ")
        )
        ->leftJoin('LOJAS', function ($join) {
            $join->on('CENTROS_CUSTO.EMPRESA_USUARIA', '=', 'LOJAS.LOJA')
                 ->where('LOJAS.ATIVA', 'S')
                 ->where('LOJAS.LOJA_FISICA', 'S');
        })
        ->whereIn(
            'CENTROS_CUSTO.OBJETO_CONTROLE',
            DB::table('lg_importa_funcionarios')
                ->select('CENTRO_CUSTO')
                ->distinct()
        )
        ->get();
}



    public function getDepartamentsRegister()  {
      return RegisteredDepartments::on('api')
        ->pluck('ID_DEPARTMENT')
        ->toArray();
    }




}
