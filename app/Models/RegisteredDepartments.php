<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegisteredDepartments extends Model
{
    use HasFactory;

    protected $connection = 'api';

    protected $table = 'DEPARTMENTS_REPORT_IT';

    protected $primaryKey = 'DEPARTMENTS_REPORT_IT';


    protected $fillable = [

        'ID_REPORT_IT',
        'COMPANY_ID',
        'ID_DEPARTMENT',
        'NAME',
        'DESCRIPTION',
        'CREATE_DATE'
    ];


    public $timestamps = false;


    public static function saveDeparments($idreportIt, $companyId, $idDepartment, $name, $description, $createDate) :void
    {
            self::UpdateOrcreate(
            [
               'ID_REPORT_IT' => $idreportIt,
            ]    ,
            
            [
                'COMPANY_ID' => $companyId,
                'ID_DEPARTMENT' => $idDepartment,
                'NAME' => $name,
                'DESCRIPTION' => $description,
                'CREATE_DATE' => $createDate
            ]);
    }




}
