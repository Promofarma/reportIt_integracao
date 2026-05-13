<?php

namespace App\Console\Commands\Disable;

use App\Console\UrlBase;
use App\Http\Headers;
use App\Models\DisableUser as DisableUserModel;
use App\Models\Users;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;

class DisableEmployees extends Command
{
    protected $signature = "report:disableemployees";
    protected $description = "Comando para desabilitar os funcionários na API Report It";

    public function getUrlBase()
    {
        $UrlBase = new UrlBase();
        return $UrlBase->getUrlBaseLg();
    }

    public function handle()
    {
        $client = new Client();
        $headers = Headers::getHeaders();
        $url_base = $this->getUrlBase();
        
        $command = "employees/disable"; 
        $urlCompleta = $url_base . $command;

        $users = new Users();

        $usersDisable = $users->getUserDisable()->orderBy('EMPLOYEE_ID')->get(); 

       
       
        if ($usersDisable->isEmpty()) {
            $this->info("Nenhum funcionário para desabilitar.");
            return;
        }

       foreach($usersDisable as $userDisable){
         
            $body = [
                "id" => (int) $userDisable->EMPLOYEE_ID
            ];

           
            
            try {
              
                $res = $client->put($urlCompleta, [
                    'headers' => $headers,
                    'json'    => $body, 
                ]);

             

                $this->info("Funcionário {$userDisable->EMPLOYEE_ID} desabilitado com sucesso!");

            } catch (GuzzleException $e) {
               
              
                $this->error("Erro ao desabilitar funcionário {$userDisable->EMPLOYEE_ID}");
            }
        }
    }

    public function getDisableUsers()
    {
        return (new DisableUserModel())->DisableUsers();
    }
}