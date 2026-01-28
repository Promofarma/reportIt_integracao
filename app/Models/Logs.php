<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logs extends Model
{
    use HasFactory;

    protected $connection = 'api';

    protected $table = 'LOG_REPORTIT';  

    protected $primaryKey = 'ID';


    protected $fillable = [
        'DESCRICAO',
        'STATUS',
        'DATA_EXECUCAO'
    ];


    public $timestamps = false;
    
    public static function createLog($descricao, $status, $data_execucao):  void
    {
          self::create([
            'DESCRICAO' => $descricao,
            'STATUS' => $status,
            'DATA_EXECUCAO' => $data_execucao
        ]);
    }
    

}
