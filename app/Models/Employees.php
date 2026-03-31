<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\RegisteredEmployees;

class Employees extends Model
{
    use HasFactory;

    protected $connection = 'api';
    protected $table = 'VW_EMPLOYEES_REPORT_IT';

    public $timestamps = false;

    public function getEmployees()
    {

        $employeesRegistered = $this->getEmployeesRegistered();

        $employeesBase = $this->getEmployeesBase()->whereNotIn('INSCRICAO_FEDERAL', $employeesRegistered);

        return $employeesBase;
    }


    public function getEmployeesBase(){

            return $this->select(
                    'COMPANY_ID',	
                    'NOME',
                    'NOME_SOCIAL',
                    'INSCRICAO_FEDERAL',
                    'COMPANYWORKPLACEID',	
                    'DEPARTMENTID',
                    'POSITIONID',	
                    'TYPE',
                    'DATA_NASCIMENTO',	
                    'NACIONALIDADE',
                    'ETNIA_DESCRICAO',	
                    'DATA_ADMISSAO',
                    'GRAU_INSTRUCAO',
                    'SEXO',
                    'E_MAIL',
                    'ESTADO_CIVIL',
                    'CEP',	
                    'ENDERECO',
                    'NUMERO',
                    'COMPLEMENTO',
                    'BAIRRO',
                    'ESTADO',
                    'CIDADE',
                    'TELEFONE',
                )->get();

    }

    public function getEmployeesRegistered()    
    {
       return RegisteredEmployees::all()->pluck('CPF')->toArray();
    }


public function getEmployeesUpdate()
{
   
    $employeesOutdated = DB::connection('api')->table('VW_EMPLOYEES_OUTDATED')->orderby('INSCRICAO_FEDERAL')->get();

    return $employeesOutdated;

    
}




  


}
