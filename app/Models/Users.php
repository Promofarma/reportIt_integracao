<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\RegisteredEmployees;
use Carbon\Carbon;

class Users extends Model
{
    use HasFactory;

    protected $connection = 'api';
    protected $primaryKey = 'ID';
    protected $table = 'USERS_REPORT_IT';
    public $timestamps = false;
    protected $guarded = [];

    public function getUsers()
    {
        $usersRegisteredIds = $this->getUsersBase()->pluck('LOGIN')->filter()->toArray();

        

        $usersBase = $this->getEmployeesBaseQuery()
            ->whereNotIn('CPF', $usersRegisteredIds)
            ->get();

        return $usersBase;
    }


    public function getUsersBase()
    {
        return $this->select('LOGIN')->get();
    }

    public function getEmployeesBaseQuery()
    {
        return RegisteredEmployees::query()->select(
            'CPF AS LOGIN',
            'CPF AS PASSWORD',
            'NAME',
            'ID_REPORT_IT AS EMPLOYEE_ID'
        );
    }

     public function getEmployeesBase()
    {
        return $this->getEmployeesBaseQuery()->get();
    }


public static function saveUsers($dados)
{
    foreach ($dados as $usuario) {
        self::updateOrCreate(
            ['ID_USER' => $usuario['id'] ?? $usuario['ID_USER']], 
            [
                'LOGIN'                => $usuario['login'] ?? null,
                'NAME'                 => $usuario['name'] ?? null,
                'EMAIL'                => $usuario['email'] ?? null,
                'PERFIL'               => $usuario['perfil'] ?? 'client',
                'ENABLE'               => $usuario['enabled'] ?? 0,
                'IS_SESMT'             => $usuario['isSESMT'] ?? 0,
                'IS_CIPA'              => $usuario['isCIPA'] ?? 0,
                'IS_EMPLOYEE'          => $usuario['isEmployee'] ?? 0,
                'IS_COMMITTEE_MEMBER'  => $usuario['isCommitteeMember'] ?? 0,
                'IS_ADMIN'             => $usuario['isAdmin'] ?? 0,
                'EMPLOYEE_ID'          => $usuario['employeeId'] ?? null,
                'REQUIRE_NEW_PASSWORD' => $usuario['requireNewPassword'] ?? 1,
                'LAST_ACCESS'          => Carbon::parse($usuario['lastAccess'])->format('d-m-Y H:i:s'),
                'INSERT_DATE_TIME'     => Carbon::parse($usuario['insertDateTime'])->format('d-m-Y H:i:s'),
                'UPDATE_DATE_TIME'     => Carbon::parse($usuario['updateDateTime'])->format('d-m-Y H:i:s'),
                'SSO_EXPIRE_DATE_TIME' => Carbon::parse($usuario['SSOExpireDateTime'])->format('d-m-Y H:i:s'),
                'VALIDATION_CODE'      => $usuario['validationCode'] ?? null,
                'SSO_KEY'              => $usuario['SSOKey'] ?? null,
            ]
        );
    }
}

}
