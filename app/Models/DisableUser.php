<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DisableUser extends Model
{
    use HasFactory;

    protected $connection = 'api';
    protected $table = 'VW_EMPLOYEES_DISABLE';

    public $timestamps = false;

    public function DisableUsers()
    {
        return $this->select(
                        'ID',
                        'NAME',
                        'EMPLOYEE_ID',
                        'DATA_RESCISAO'
                    )->get();
    }



}
