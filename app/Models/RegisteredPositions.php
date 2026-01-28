<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegisteredPositions extends Model
{
    use HasFactory;

    protected $connection = 'api';

    protected $table = 'POSITIONS_REPORT_IT';

    protected $primaryKey = 'POSITIONS_REPORT_IT';


    protected $fillable = [

        'ID_REPORT_IT',
        'COMPANY_ID',
        'ID_POSITION',
        'NAME',
        'DESCRIPTION',
        'CREATE_DATE'
    ];


    public $timestamps = false;


    public static function savePositions($idreportIt, $companyId, $idPositions, $name, $description, $createDate) :void
    {
            self::UpdateOrcreate(
            [
               'ID_REPORT_IT' => $idreportIt,
            ]    ,
            
            [
                'COMPANY_ID' => $companyId,
                'ID_POSITION' => $idPositions,
                'NAME' => $name,
                'DESCRIPTION' => $description,
                'CREATE_DATE' => $createDate
            ]);
    }




}
