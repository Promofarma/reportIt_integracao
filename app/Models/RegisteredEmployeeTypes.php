<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegisteredEmployeeTypes extends Model
{
    use HasFactory;

    protected $connection = 'api';

    protected $table = 'EMPLOYEE_TYPES_REPORT_IT';

    protected $primaryKey = 'EMPLOYEE_TYPE_REPORT_I';


    protected $fillable = [

        'ID_REPORT_IT',
        'COMPANY_ID',
        'TITLE',
        'CREATE_DATE'
    ];


    public $timestamps = false;


    public static function saveEmployeeTypes($idreportIt, $companyId, $title, $createDate) :void
    {
            self::UpdateOrcreate(
            [
               'ID_REPORT_IT' => $idreportIt,
            ]    ,
            
            [
                'COMPANY_ID' => $companyId,
                'TITLE' => $title,
                'CREATE_DATE' => $createDate
            ]);
    }




}
