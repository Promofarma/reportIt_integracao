<?php

namespace App\Console\Commands\Update;

use GuzzleHttp\Client;
use App\Http\BodyToken;
use App\Http\Headers;
use Illuminate\Console\Command;
use App\Console\UrlBase;
use App\Models\Departments;
use App\Models\RegisteredDepartments;
use App\Models\Logs;
use GuzzleHttp\Psr7\Header;
use Soap\Url;

class UpdateDepartment extends Command
{
    protected $signature = "report:updatedepartment";

    protected $description = "Comando para atualizar o departamentos na API Report It";



    protected function getUrlBase()
    {

        $UrlBase = new UrlBase();
        return $UrlBase->getUrlBaseLg();
    }


    public function handle()
    {


        $allDepartaments = $this->getAllDepartaments();
        $registeredDepartments = $this->getDepartamentsRegister()->keyBy('ID_DEPARTMENT');

        $departamentsDivergentes = $allDepartaments
            ->filter(function ($item) use ($registeredDepartments) {

                return $registeredDepartments->has($item->OBJETO_CONTROLE)
                    && trim($item->DESCRICAO) !== trim(
                        $registeredDepartments[$item->OBJETO_CONTROLE]->NAME
                    );
            })
            ->map(function ($item) use ($registeredDepartments) {

                $registered = $registeredDepartments[$item->OBJETO_CONTROLE];

                return [

                    'company_id'        => $registered->COMPANY_ID,
                    'id_report_it'      => $registered->ID_REPORT_IT,
                    'objeto_controle'   => $item->OBJETO_CONTROLE,
                    'descricao'     => $item->DESCRICAO
                ];
            });

        $client = new Client();
        $header = Headers::getHeaders();
        $url_base = $this->getUrlBase();


        $command = "departments/update";
        $urlCompleta = $url_base . $command;


        foreach ($departamentsDivergentes as $updateDepartament) {

            $body = [

                "id" => $updateDepartament['id_report_it'],
                "companyId" => $updateDepartament['company_id'],
                "code" => $updateDepartament['objeto_controle'],
                "name" => $updateDepartament['descricao'],
                "description" => $updateDepartament['descricao'],


            ];


            try {
                $res = $client->put($urlCompleta, [
                    'headers' => $header,
                    'json' => $body,

                ]);

                $response = json_decode($res->getBody()->getContents(), true);

                Logs::createLog($command . " - " . $updateDepartament['descricao'], "sucess", date_format(now(), 'd-m-Y H:i:s'));

                $this->info("Departamento atualizado: {$updateDepartament['descricao']}");
            } catch (\GuzzleHttp\Exception\ClientException $e) {


                Logs::createLog($command . " - " . $updateDepartament['descricao'], "erro", date_format(now(), 'd-m-Y H:i:s'));

                $this->error(
                    "Erro ao enviar departamento {$updateDepartament['descricao']}: " .
                        $e->getResponse()->getBody()->getContents()
                );
            }
        }
    }


    public function getAllDepartaments()
    {
        return  Departments::getDepartamentsAll();
    }

    public function getDepartamentsRegister()
    {

        return  RegisteredDepartments::all();
    }
}
