<?php

namespace App\Console\Commands\Update;

use Soap\Url;
use App\Models\Logs;
use App\Http\Headers;
use GuzzleHttp\Client;
use App\Http\BodyToken;
use App\Console\UrlBase;
use App\Models\Positions;
use App\Models\Departments;
use GuzzleHttp\Psr7\Header;
use Illuminate\Console\Command;
use App\Models\RegisteredPositions;
use App\Models\RegisteredDepartments;

class updatePositions extends Command
{
    protected $signature = "report:updatepositions";

    protected $description = "Comando para atualizar o cargo na API Report It";



    protected function getUrlBase()
    {

        $UrlBase = new UrlBase();
        return $UrlBase->getUrlBaseLg();
    }


    public function handle()
    {
        $allPositions = $this->getAllPositions();
        $registeredPositions = $this->getPositionsRegister()->keyBy('ID_POSITION');  
        $positionsDivergentes = $allPositions
            ->filter(function ($item) use ($registeredPositions) {
                return $registeredPositions->has($item->cargo_folha)
                    && trim($item->descricao) !== trim(
                        $registeredPositions[$item->cargo_folha]->NAME
                    );
            })
            ->map(function ($item) use ($registeredPositions) {
                $registered = $registeredPositions[$item->cargo_folha];
                return [

                    'company_id'        => $registered->COMPANY_ID,
                    'id_report_it'      => $registered->ID_REPORT_IT,
                    'cargo_folha'   => $item->cargo_folha,
                    'descricao'     => $item->descricao
                ];
            });
        $client = new Client();
        $header = Headers::getHeaders();
        $url_base = $this->getUrlBase();
        $command = "positions/update";
        $urlCompleta = $url_base . $command;
        foreach ($positionsDivergentes as $updatePositions) {
             $body = [
                "id" => $updatePositions['id_report_it'],
                "companyId" => $updatePositions['company_id'],
                "code" => $updatePositions['cargo_folha'],
                "title" => $updatePositions['descricao'],
                "description" => $updatePositions['descricao'],
            ];
            try {
                $res = $client->put($urlCompleta, [
                    'headers' => $header,
                    'json' => $body,

                ]);

                $response = json_decode($res->getBody()->getContents(), true);
                Logs::createLog($command . " - " . $updatePositions['descricao'], "sucess", date_format(now(), 'd-m-Y H:i:s'));
                $this->info("Departamento atualizado: {$updatePositions['descricao']}");
            } catch (\GuzzleHttp\Exception\ClientException $e) {
                Logs::createLog($command . " - " . $updatePositions['descricao'], "erro", date_format(now(), 'd-m-Y H:i:s'));
                $this->error(
                    "Erro ao enviar departamento {$updatePositions['descricao']}: " .
                        $e->getResponse()->getBody()->getContents()
                );
            }
        }
    }


    public function getAllPositions()
    {
        return  Positions::getPositionsAll();
    }

    public function getPositionsRegister()
    {

        return  RegisteredPositions::all();
    }
}
