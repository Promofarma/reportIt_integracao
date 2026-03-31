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


        $commandUpdate = "employees/update";
        $urlCompletaUpdate = $url_base . $command;

       
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
              
                Users::where('ID_USER', $disableUser->ID)->update(['ENABLE' => 0]);

                $bodyUpdate = [
                    "id" => $disableUser->ID_REPORT_IT,
                    "resignationDate" => Carbon::parse($disableUser->DATA_RESCISAO)->format('d-m-Y'),
                ];

                $resUpdate = $client->put($urlCompletaUpdate, [
                    'headers' => $headers,
                    'json'    => $bodyUpdate, 
                ]);

                $this->info("Usuário {$disableUser->ID} desabilitado com sucesso!");

            } catch (GuzzleException $e) {
             
                dd($e);
                $this->error("Erro ao desabilitar usuário {$disableUser->ID}");
            }
        }
    }

    public function getDisableUsers()
    {
        return (new DisableUserModel())->DisableUsers();
    }
}