<?php 

namespace App\Console\Commands\Create;  

use App\Models\Logs;
use App\Http\Headers;
use GuzzleHttp\Client;
use App\Http\BodyToken;
use App\Console\UrlBase;
use App\Models\Users;
use Illuminate\Console\Command;


class CreateUser extends Command
{ 

    protected $signature = "report:createuser";

    protected $description = "Comando para criar usuários na API Report It";


     protected function getUrlBase() 
    { 
        $UrlBase = new UrlBase();
        return $UrlBase->getUrlBaseLg();
    }

    public function handle()
    {
        $client = new Client();
        $headers = Headers::getHeaders();
        $url_base = $this->getUrlBase();

        $command = "/users/add";
        $urlCompleta = $url_base . $command;

         
        $usersBase = new Users();
        $users = $usersBase->getUsers();

      
      
      foreach ($users as $user) {


    $body = [
        
        "login" => (string) $user->LOGIN,
        "password" => (string) $user->PASSWORD,
        "name" => (string) $user->NAME,
        "email" => null,
        "perfil" => "client",
        "employeeId" => (string) $user->EMPLOYEE_ID,
        "isSESMT" => false,
        "isCIPA" => false,
        "isEmployee" => true,
        "isCommitteeMember" => false,
        "isAdmin" => false,
        "principalClientId" => 0,
        "principalPartnerId" => 0
    ];

    try {
        $res = $client->post($urlCompleta, [
            'headers' => $headers,
            'json'    => $body, 
        ]);

        $response = json_decode($res->getBody()->getContents(), true);

       Logs::createLog($command. " - " . $user->NAME, "sucess", date_format(now(), 'd-m-Y H:i:s'));

        $this->info("usuário criado: {$user->NAME}");

    } catch (\GuzzleHttp\Exception\ClientException $e) {

     
       
     Logs::createLog($command. " - " . $user->NAME, "erro", date_format(now(), 'd-m-Y H:i:s'));

        $this->error(
            "Erro ao criar usuarios {$user->NAME}: " .
            $e->getResponse()->getBody()->getContents()
        );
    }
}



    }


    




}