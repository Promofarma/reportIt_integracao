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
        $usersRegistered = $this->getUsersBase();
        $usersBase = $this->getEmployeesBase()->whereNotIn('EMPLOYEE_ID', $usersRegistered->pluck('EMPLOYEE_ID'));
        return $usersBase;
    }


    public function getUsersBase(){

            return $this->select(
                    'ID',
                    'ID_USER',
                    'LOGIN',
                    'PERFIL',
                    'NAME',
                    'EMAIL',
                    'ENABLE',
                    'IS_SESMT',
                    'IS_CIPA',
                    'IS_EMPLOYEE',
                    'IS_COMMITTEE_MEMBER',
                    'IS_ADMIN',
                    'EMPLOYEE_ID',
                    'REQUIRE_NEW_PASSWORD',
                    'LAST_ACCESS',
                    'VALIDATION_CODE',
                    'SSO_KEY',
                    'SSO_EXPIRE_DATE_TIME',
                    'INSERT_DATE_TIME',
                    'UPDATE_DATE_TIME',
                )->get();
    }

       public function getEmployeesBase(){

            return RegisteredEmployees::query()->select(
                      
                    'CPF AS LOGIN',
                    'CPF AS PASSWORD',
                    'NAME',
                    'ID_REPORT_IT AS EMPLOYEE_ID'
                )->get();
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
