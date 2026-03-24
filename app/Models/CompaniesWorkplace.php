<?php

namespace App\Models;

use App\Models\RegisteredDepartments;

class CompaniesWorkplace extends Companies
{

    public function getCompaniesToRegister()
    {
        
        $AllCompaniesRegistered = self::getAllCompaniesRegistered();

        $AllCompanies = self::getAllCompanies();

        return $AllCompanies->whereNotIn('INSCRICAO_FEDERAL', $AllCompaniesRegistered->pluck('DOCUMENT'));

    }

   
    public static function getAllCompanies()
    {
       
        return self::query()
            ->select(
                'EMPRESAS_USUARIAS.EMPRESA_USUARIA',
                'PESSOAS_JURIDICAS.INSCRICAO_FEDERAL',
                'EMPRESAS_USUARIAS.NOME',
            )
            ->leftJoin('PESSOAS_JURIDICAS', 'EMPRESAS_USUARIAS.ENTIDADE', '=', 'PESSOAS_JURIDICAS.ENTIDADE')
            ->get();
    }

    public static function getAllCompaniesRegistered()
    {
        return RegisteredDepartments::all();
    }
}