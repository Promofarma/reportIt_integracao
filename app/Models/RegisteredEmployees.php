<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'UPDATE_DATE'
    ];


    public $timestamps = false;


    public static function saveEmployees($idreportIt, $companyId,  $departmentId, $positionId, $typeId, $companyWorkplaceId, $cpf, $name, $dateCreate, $updateDate) :void
    {
            self::UpdateOrcreate(
                [
                'ID_REPORT_IT' => $idreportIt
             
            ]    ,
            
            [
                'COMPANY_ID' => $companyId,
                'CPF' => $cpf,
                'COMPANY_ID' => $companyId,
                'DEPARTMENT_ID' => $departmentId,
                'POSITION_ID' => $positionId,
                'TYPE_ID' => $typeId,
                'COMPANY_WORKPLACE_ID' => $companyWorkplaceId,  
                'NAME' => $name,
                'DATE_CREATE' => $dateCreate,
                'UPDATE_DATE' => $updateDate
            ]);
    }




}
