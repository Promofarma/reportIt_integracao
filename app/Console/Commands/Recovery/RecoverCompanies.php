<?php

namespace App\Console\Commands\Recovery;

use Illuminate\Console\Command;
use App\Http\Headers;
use App\Console\UrlBase;
use GuzzleHttp\Client;
use App\Models\RegisteredCompanies;
use App\Models\Logs;

class RecoverCompanies extends Command
{
    protected $signature = "report:recovercompany";
    protected $description = "Comando para recuperar a empresa na API Report It";

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
        $command = "companies/getAll";
        $urlCompleta = $url_base . $command;

        try {
            $res = $client->get($urlCompleta, [
                'headers' => $headers
            ]);

            $response = json_decode($res->getBody()->getContents(), true);

            RegisteredCompanies::saveCompany($response);

            Logs::createLog($command . " - Carga de Empresas", "sucess", date('d-m-Y H:i:s'));

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            
            Logs::createLog($command . " - Erro Carga", "erro", date('d-m-Y H:i:s'));

            $this->error(
                "Erro ao salvar Estabelecimentos: " .
                $e->getResponse()->getBody()->getContents()
            );
        }
    } 
} 