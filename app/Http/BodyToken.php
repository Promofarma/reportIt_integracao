<?php

namespace App\Http;

use Illuminate\Support\Env;

class BodyToken
{
    /**
     * Retorna os headers padrão para requisições HTTP.
     *
     * @return array
     */
    public static function getBody()
    {
        return [
            'login' => env('API_USUARIO'),
             'password' => env('API_SENHA')
        ];
    }
}
