<?php

namespace App\Console\Commands\Create;

use GuzzleHttp\Client;
use App\Http\Headers;
use Illuminate\Console\Command;
use App\Console\UrlBase;
use App\Models\CompaniesWorkplace;
use App\Models\Logs;


class CreateCompaniesWorkplaces extends Command
{

    protected $signature = "report:createcompaniesworkplaces";
    protected $description = "Comando para criar locais de trabalho na API Report It";

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
        $command = "companyworkplaces/add";
        $urlCompleta = $url_base . $command;
        $companiesModel = new CompaniesWorkplace();
        $companies = $companiesModel->getCompaniesToRegister();

         
        foreach ($companies as $companies) {

            $body = [
                "companyId"   => (int) ENV('API_COMPANY_ID'),
                "name"        => $companies->NOME,
                "type"        => 'CNPJ',
                "document"    => $companies->INSCRICAO_FEDERAL,
                "subscriptionType" => 'CNPJ',
                "subscriptionDocument" => $companies->INSCRICAO_FEDERAL,
                "technicalResponsible" => "Informar posteriormente",
                "technicalResponsibleSignature" => "Informar posteriormente",
                "companyResponsible" => "Informar posteriormente",
                "companyResponsibleSignature" => "Informar posteriormente"

            ];

            try {
                $res = $client->post($urlCompleta, [
                    'headers' => $headers,
                    'json'    => $body,
                ]);

                Logs::createLog($command . " - " . $companies->NOME, "sucess", date_format(now(), 'd-m-Y H:i:s'));
                $this->info("Empresa enviada: {$companies->NOME} - {$companies->INSCRICAO_FEDERAL}");
            } catch (\GuzzleHttp\Exception\ClientException $e) {
                Logs::createLog($command . " - " . $companies->NOME, "erro", date_format(now(), 'd-m-Y H:i:s'));
                $this->error(
                    "Erro ao enviar empresa  {$companies->NOME} - {$companies->INSCRICAO_FEDERAL}: " .
                        $e->getResponse()->getBody()->getContents()
                );
            }
        }
    }
}
