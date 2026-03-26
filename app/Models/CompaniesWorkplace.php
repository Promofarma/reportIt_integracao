<?php

namespace App\Models;

use App\Models\RegisteredCompanyWorkplace;

class CompaniesWorkplace extends Companies
{

    protected $connection = 'sqlsrv';

    public function getCompaniesToRegister()
    {
        
        $AllCompaniesRegistered = self::getAllCompaniesRegistered();

        $AllCompanies = self::getAllCompanies();

        return $AllCompanies->whereNotIn('INSCRICAO_FEDERAL', $AllCompaniesRegistered->pluck('DOCUMENT'));

    }

   
    public static function getAllCompaniesRegistered()
    {
        return RegisteredCompanyWorkplace::all();
    }
}