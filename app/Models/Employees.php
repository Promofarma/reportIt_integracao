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

        $employeesBase = $this->getEmployeesBase()->whereNotIn('inscricao_federal', $employeesRegistered)->get();

        return $employeesBase;
    }


    public function getEmployeesBase(){

            return $this->select(
                    'COMPANY_ID',
                    'NOME',
                    'INSCRICAO_FEDERAL',
                    'COMPANYWORKPLACEID',
                    'DEPARTMENTID',
                    'POSITIONID',
                    'TYPE'
                )->get();

    }

    public function getEmployeesRegistered()    
    {
       return RegisteredEmployees::all()->pluck('CPF')->toArray();
    }


public function getEmployeesUpdate()
{
    $employeesBase = $this->getEmployeesBase();

    $employeesRegistered = RegisteredEmployees::all()
        ->keyBy('CPF');

    return $employeesBase->filter(function ($employee) use ($employeesRegistered) {

        if (!isset($employeesRegistered[$employee->INSCRICAO_FEDERAL])) {
            return false;
        }

        $registered = $employeesRegistered[$employee->INSCRICAO_FEDERAL];

        return
            $employee->COMPANYWORKPLACEID != $registered->COMPANY_WORKPLACE_ID ||
            $employee->DEPARTMENTID        != $registered->DEPARTMENT_ID ||
            $employee->POSITIONID          != $registered->POSITION_ID ||
            $employee->TYPE                != $registered->TYPE_ID;

    })->map(function ($employee) use ($employeesRegistered) {

        $registered = $employeesRegistered[$employee->INSCRICAO_FEDERAL];

        return [
            'COMPANY_ID'          => $employee->COMPANY_ID,
            'NOME'                => $employee->NOME,
            'INSCRICAO_FEDERAL'   => $employee->INSCRICAO_FEDERAL,
            'COMPANYWORKPLACEID' => $employee->COMPANYWORKPLACEID,
            'DEPARTMENTID'       => $employee->DEPARTMENTID,
            'POSITIONID'         => $employee->POSITIONID,
            'TYPE'                => $employee->TYPE,
            'ID_REPORT_IT'        => $registered->ID_REPORT_IT,
        ];

    })->values();
}




  


}
