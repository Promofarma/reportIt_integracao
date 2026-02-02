<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegisteredCompany extends Model
{
    use HasFactory;

    protected $connection = 'api';

    protected $table = 'COMPANY_WORKPLACES_REPORT_IT';

    protected $primaryKey = 'COMPANY_WORKPLACE_REPORT_IT';


    protected $fillable = [

        'ID_REPORT_IT',
        'COMPANY_ID',
        'NAME',
        'DOCUMENT'
    ];


    public $timestamps = false;


    public static function saveCompany($idreportIt, $companyId,  $name, $document): void
    {
        self::UpdateOrcreate(
            [
                'ID_REPORT_IT' => $idreportIt,
            ],

            [
                'COMPANY_ID' => $companyId,
                'NAME' => $name,
                'DOCUMENT' => $document
            ]
        );
    }
}
