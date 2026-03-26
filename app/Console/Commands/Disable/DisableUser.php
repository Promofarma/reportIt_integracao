<?php

namespace App\Console\Commands\Disable;

use Illuminate\Console\Command;
use App\Http\Headers;
use App\Console\UrlBase;
use GuzzleHttp\Client;
use App\Models\Users;
use App\Models\DisableUser as DisableUserModel;
use GuzzleHttp\Exception\GuzzleException;

class DisableUser extends Command
{
    protected $signature = "report:disableusers";
    protected $description = "Comando para desabilitar os usuários na API Report It";

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

        $command = "users/disable"; 
        $urlCompleta = $url_base . $command;

       
        $disableUsers = $this->getDisableUsers();

        if ($disableUsers->isEmpty()) {
            $this->info("Nenhum usuário para desabilitar.");
            return;
        }

        foreach ($disableUsers as $disableUser) {
            
          
            $body = [
                "id" => $disableUser->ID
            ];

            try {
              
                $res = $client->put($urlCompleta, [
                    'headers' => $headers,
                    'json'    => $body, 
                ]);

                $responseBody = $res->getBody()->getContents();
                
              
                Users::where('ID_USER', $disableUser->ID)->delete();

                $this->info("Usuário {$disableUser->ID} desabilitado com sucesso!");
            

            } catch (GuzzleException $e) {
             

                $this->error("Erro ao desabilitar usuário {$disableUser->ID}");
            }
        }
    }

    public function getDisableUsers()
    {
        return (new DisableUserModel())->DisableUsers();
    }
}