<?php 

namespace App\Console\Commands;  

use GuzzleHttp\Client;
use App\Http\BodyToken;
use App\Http\Headers;
use Illuminate\Console\Command;
use App\Console\UrlBase;

class RecoverToken extends Command
{ 

    protected $signature = "report:recovertoken";

    protected $description = "Comando para recuperar o token de autenticação da API Report It";


    protected function getUrlBase() 
    { 
        $UrlBase = new UrlBase();
        return $UrlBase->getUrlBaseLg();
    }

   
    public function getCredentials() 
    { 
        
        $name = env('API_USUARIO');
        $password = env('API_SENHA');
        return array( 'name' => $name, 'password' => $password );


    }


 public function handle()
{
    $client = new Client();
    $headers = Headers::getHeaders();
    $bodyToken = BodyToken::getBody();
    $url_base = $this->getUrlBase();

    $comandToken = "users/login";
    $urlCompleta = $url_base . $comandToken;


    $res = $client->post($urlCompleta, [
            'headers' => $headers,
            'body'    => json_encode($bodyToken),
        ]);

     $response = $res->getBody()->getContents();
     $data = json_decode($response, true);


    $token = $data['accessToken'];

     if ($token) {
            $path = base_path('.env');
            $env = file_get_contents($path);

            if (preg_match('/^API_TOKEN=.*/m', $env)) {

                $env = preg_replace('/^API_TOKEN=.*/m', 'API_TOKEN=' . $token, $env);
            } else {

                $env .= "\nAPI_TOKEN=" . $token;
            }

            file_put_contents($path, $env);
        }

}

  
  

}

