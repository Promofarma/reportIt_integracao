<?php

namespace App\Models;

use App\Models\Users;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\DB;

class RegisteredEmployees extends Model
{
    use HasFactory;

    protected $connection = 'api';

    protected $table = 'EMPLOYEES_REPORT_IT';

    protected $primaryKey = 'EMPLOYEE_REPORT_IT';


    protected $fillable = [

            'ID_REPORT_IT',
            'COMPANY_ID',
            'DEPARTMENT_ID',
            'POSITION_ID',
            'TYPE_ID',
            'COMPANY_WORKPLACE_ID',
            'CPF',
            'NAME',
            'DATE_CREATE',
            'UPDATE_DATE',
            'DATA_NASCIMENTO',
            'NACIONALIDADE',
            'ETNIA_DESCRICAO',
            'DATA_ADMISSAO',
            'GRAU_INSTRUCAO_DESCRICAO',
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
            'TELEFONE'
    ];


    public $timestamps = false;


    public static function saveEmployees($dados) :void
    {
          
           foreach($dados as $dados){
            self::updateOrCreate(
                ['ID_REPORT_IT' => $dados['id']],
                [
                    'COMPANY_ID' => $dados['companyId'],
                    'DEPARTMENT_ID' => $dados['departmentId'],
                    'POSITION_ID' => $dados['positionId'],
                    'TYPE_ID' => $dados['type'],
                    'COMPANY_WORKPLACE_ID' => $dados['companyWorkPlaceId'],
                    'CPF' => $dados['cpf'],
                    'NAME' => $dados['name'],
                    'DATE_CREATE' => Carbon::parse($dados['insertDateTime'])->format('d-m-Y H:i:s'),
                    'UPDATE_DATE' => Carbon::parse($dados['updateDateTime'])->format('d-m-Y H:i:s'),
                    'DATA_NASCIMENTO' => !empty($dados['birthDate']) ? Carbon::parse(str_replace('/', '-', $dados['birthDate']))->format('Y-m-d')  : null,
                    'NACIONALIDADE' =>  $dados['birthCountry'],
                    'ETNIA_DESCRICAO' => $dados['raceColor'],
                    'DATA_ADMISSAO' => !empty($dados['admissionDate'])  ? Carbon::parse(str_replace('/', '-', $dados['admissionDate']))->format('Y-m-d')  : null,
                    'GRAU_INSTRUCAO_DESCRICAO' => $dados['educationLevel'],
                    'SEXO' => $dados['gender'],
                    'E_MAIL' => $dados['email'],
                    'ESTADO_CIVIL' => $dados['civilState'],
                    'CEP' => $dados['cep'],
                    'ENDERECO' => $dados['placeAddress'],
                    'NUMERO' => $dados['placeNumber'],
                    'COMPLEMENTO' => $dados['placeComplement'],
                    'BAIRRO' => $dados['placeDistrict'],
                    'ESTADO' => $dados['placeState'],
                    'CIDADE' => $dados['placeCity'],
                    'TELEFONE' => $dados['phone'],


                ]
            );


            

        }
    
    
    }


    public function getEmployeesUser(){

        $users = User::all()->pluck('login')->toArray();

      $demitidos = DB::connection('sqlsrv')
                ->table('LG_IMPORTA_FUNCIONARIOS_DEMITIDOS')
                ->select(DB::raw("REPLACE(REPLACE(INSCRICAO_FEDERAL, '.', ''), '-', '') AS CPF"))
                ->get()
                ->pluck('CPF')
                ->flip()
                ->toArray();

        return $this->select(
                'EMPLOYEES_REPORT_IT.CPF AS LOGIN',
                'EMPLOYEES_REPORT_IT.CPF AS PASSWORD',
                'EMPLOYEES_REPORT_IT.NAME',
                'EMPLOYEES_REPORT_IT.ID_REPORT_IT AS EMPLOYEE_ID'
            )
            ->leftJoin('USERS_REPORT_IT', function ($join) {
                $join->on('EMPLOYEES_REPORT_IT.CPF', '=', 'USERS_REPORT_IT.LOGIN');
            })
            ->whereNull('USERS_REPORT_IT.LOGIN')
            ->get()
            ->filter(fn($employee) => !isset($demitidos[$employee->LOGIN]));
        
    }    



}
