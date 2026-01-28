<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Positions extends Model
{
    use HasFactory;

    protected $connection = 'sqlsrv';
 
    protected $table = 'cargos_folha';

    protected $primaryKey = 'cargo_folha';

    public $timestamps = false;



    public function getPositions()
    {

        $registerPositions = $this->getPositionsRegister();
        $allPositions = $this->getPositionsAll();


        return $allPositions->whereNotIn('cargo_folha', $registerPositions);


    }


    public static function getPositionsAll(){

      return static::query()->select('cargo_folha', 'descricao')
            ->whereIn(
                'cargos_folha.cargo_folha',
                DB::table('lg_importa_funcionarios')
                    ->select('cargo')
                    ->distinct()
            )
            ->get();

    }


    public function getPositionsRegister(){

         return RegisteredPositions::on('api')
        ->pluck('ID_POSITION')
        ->toArray();

    }
}
