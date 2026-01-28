<?php 

namespace App\Console\Commands\Create;  

use GuzzleHttp\Client;
use App\Http\BodyToken;
use App\Http\Headers;
use Illuminate\Console\Command;
use App\Console\UrlBase;
use App\Models\Departments;
use App\Models\Logs;


class CreateDepartaments extends Command
{ 

    protected $signature = "report:createdepartaments";

    protected $description = "Comando para criar departamentos na API Report It";


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

    
        $command = "departments/add";
        $urlCompleta = $url_base . $command;


        $departamentsModel = new Departments();
        $departaments = $departamentsModel->getDepartaments()->where('OBJETO_CONTROLE', '=', '1009');

      

      foreach ($departaments as $departament) {

    $body = [
        "companyId"   => (int) ENV('API_COMPANY_ID'),
        "code"        => (string) $departament->OBJETO_CONTROLE,
        "name"        => $departament->DESCRICAO,
        "description" => $departament->DESCRICAO,
    ];

    try {
        $res = $client->post($urlCompleta, [
            'headers' => $headers,
            'json'    => $body, 
        ]);

    //    $response = json_decode($res->getBody()->getContents(), true);

        Logs::createLog($command. " - " . $departament->DESCRICAO, "sucess", date_format(now(), 'd-m-Y H:i:s'));

        $this->info("Departamento enviado: {$departament->NOME}");


    } catch (\GuzzleHttp\Exception\ClientException $e) {
       

         Logs::createLog($command. " - " . $departament->DESCRICAO, "erro", date_format(now(), 'd-m-Y H:i:s'));

        $this->error(
            "Erro ao enviar departamento {$departament->OBJETO_CONTROLE}: " .
            $e->getResponse()->getBody()->getContents()
        );
    }
}



    }


    




}