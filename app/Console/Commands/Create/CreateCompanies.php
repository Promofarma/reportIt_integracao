<?php 

namespace App\Console\Commands\Create;  

use GuzzleHttp\Client;
use App\Http\Headers;
use Illuminate\Console\Command;
use App\Console\UrlBase;
use App\Models\Companies;
use App\Models\Logs;


class CreateCompanies extends Command
{ 

    protected $signature = "report:createcompanies";

    protected $description = "Comando para criar empresas na API Report It";


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

    
        $command = "companies/add";
        $urlCompleta = $url_base . $command;


        $companiesModel = new Companies();
        $companies = $companiesModel->getCompaniesToRegister();


     

      foreach ($companies as $companies) {

    $body = [
       "code" => $companies->EMPRESA_USUARIA,   
       "type" => "CNPJ",
       "document" => $companies->INSCRICAO_FEDERAL,
       "name" => $companies->NOME,
        "technicalResponsible" => "Informar posteriormente",
        "technicalResponsibleSignature" => "Informar posteriormente",
        "companyResponsible"=> "Informar posteriormente",
        "companyResponsibleSignature"=> "Informar posteriormente"
       
    ];

    try {
        $res = $client->post($urlCompleta, [
            'headers' => $headers,
            'json'    => $body, 
        ]);

    //    $response = json_decode($res->getBody()->getContents(), true);

        Logs::createLog($command. " - " . $companies->NOME, "sucess", date_format(now(), 'd-m-Y H:i:s'));

        $this->info("Empresa enviada: {$companies->NOME} - {$companies->INSCRICAO_FEDERAL}");


    } catch (\GuzzleHttp\Exception\ClientException $e) {
       

         Logs::createLog($command. " - " . $companies->NOME, "erro", date_format(now(), 'd-m-Y H:i:s'));

        $this->error(
            "Erro ao enviar empresa  {$companies->NOME} - {$companies->INSCRICAO_FEDERAL}: " .
            $e->getResponse()->getBody()->getContents()
        );
    }
}



    }


    




}