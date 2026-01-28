<?php 

namespace App\Console\Commands\Create;  

use GuzzleHttp\Client;
use App\Http\BodyToken;
use App\Http\Headers;
use Illuminate\Console\Command;
use App\Console\UrlBase;
use App\Models\CompanyWorkplaces;


class CreateCompanyWorkplace  extends Command
{ 

    protected $signature = "report:createcompanyworkplace";

    protected $description = "Comando para criar funcionarios na API Report It";


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


        $companyWorkplacesModel = new CompanyWorkplaces();
        $companyWorkplaces = $companyWorkplacesModel->getCompanyWorkplaces()->where('inscricao_federal', '=', '456.477.298-86');

       

      foreach ($companyWorkplaces as $companyWorkplace) {

  

    $body = [
        "companyId"   => 1,
        "name"        => $companyWorkplace->nome,
        "type"        => "cpf",
        "document"   => $companyWorkplace->inscricao_federal,
        "subscriptionType" => "cpf",
        "subscriptionDocument" => $companyWorkplace->inscricao_federal
    ];

    try {
        $res = $client->post($urlCompleta, [
            'headers' => $headers,
            'json'    => $body, 
        ]);

        $response = json_decode($res->getBody()->getContents(), true);

        $this->info("Departamento enviado: {$companyWorkplace->NOME}");

    } catch (\GuzzleHttp\Exception\ClientException $e) {

        $this->error(
            "Erro ao enviar departamento {$companyWorkplace->OBJETO_CONTROLE}: " .
            $e->getResponse()->getBody()->getContents()
        );
    }
}



    }


    




}