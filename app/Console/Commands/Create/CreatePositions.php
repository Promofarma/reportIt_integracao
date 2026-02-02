<?php 

namespace App\Console\Commands\Create;  

use App\Models\Logs;
use App\Http\Headers;
use GuzzleHttp\Client;
use App\Http\BodyToken;
use App\Console\UrlBase;
use App\Models\Positions;
use Illuminate\Console\Command;


class CreatePositions extends Command
{ 

    protected $signature = "report:createpositions";

    protected $description = "Comando para criar posições na API Report It";


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

    

        $command = "positions/add";
        $urlCompleta = $url_base . $command;


         
        $positionsModel = new Positions();
        $positions = $positionsModel->getPositions();

       

       
      foreach ($positions as $position) {


    $body = [
        "companyId"   => (int) ENV('API_COMPANY_ID'),
        "code"        => (string) $position->cargo_folha,
        "title"        => $position->descricao,
        "description" => $position->descricao,
    ];

    try {
        $res = $client->post($urlCompleta, [
            'headers' => $headers,
            'json'    => $body, 
        ]);

        $response = json_decode($res->getBody()->getContents(), true);

       Logs::createLog($command. " - " . $position->DESCRICAO, "sucess", date_format(now(), 'd-m-Y H:i:s'));

        $this->info("cargos criados: {$position->DESCRICAO}");

    } catch (\GuzzleHttp\Exception\ClientException $e) {


     Logs::createLog($command. " - " . $position->DESCRICAO, "erro", date_format(now(), 'd-m-Y H:i:s'));

        $this->error(
            "Erro ao enviar cargos {$position->DESCRICAO}: " .
            $e->getResponse()->getBody()->getContents()
        );
    }
}



    }


    




}