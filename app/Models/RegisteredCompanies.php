<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegisteredCompanies extends Model
{
    use HasFactory;

    protected $connection = 'api';

    protected $table = 'COMPANY_REPORT_IT';

    protected $primaryKey = 'COMPANY_REPORT_IT';


    protected $fillable = [

       	'ID_REPORT_IT' ,
        'CODE' ,
        'TYPE' ,
        'DOCUMENT',
        'NAME' ,
        'INSERT_DATE_TIME' ,
        'UPDATE_DATE_TIME' 
    ];


    public $timestamps = false;


    public static function saveCompany($dados): void
    {
        foreach($dados as $companies){
            self::updateOrCreate(
                ['ID_REPORT_IT' => $companies['id']],
                [
                    'CODE' => $companies['code'],
                    'TYPE' => $companies['type'],
                    'DOCUMENT' => $companies['document'],
                    'NAME' => $companies['name'],
                    'INSERT_DATE_TIME' => Carbon::parse($companies['insertDateTime'])->format('d-m-Y H:i:s'),
                    'UPDATE_DATE_TIME' => Carbon::parse($companies['updateDateTime'])->format('d-m-Y H:i:s'),


                ]
            );

        }

    }
}
