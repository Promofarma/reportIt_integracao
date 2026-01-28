<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyWorkplaces extends Model
{
    use HasFactory;

    protected $connection = 'sqlsrv';
    protected $table = 'lg_importa_funcionarios';

    public $timestamps = false;

    public function getCompanyWorkplaces()
    {
        return $this->select('nome', 'inscricao_federal')
            ->distinct()
            ->get();
    }

}
