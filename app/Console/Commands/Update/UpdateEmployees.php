<?php

namespace App\Console\Commands\Update;

use Soap\Url;
use App\Models\Logs;
use App\Http\Headers;
use GuzzleHttp\Client;
use App\Console\UrlBase;
use App\Models\Positions;
use Illuminate\Console\Command;
use App\Models\RegisteredPositions;
use App\Models\Employees;


class UpdateEmployees extends Command
{
    protected $signature = "report:updateemployees";

    protected $description = "Comando para atualizar os funcionários na API Report It";


    protected function getUrlBase()
    {
        $UrlBase = new UrlBase();
        return $UrlBase->getUrlBaseLg();
    }

    public function handle()
    {
        $employees = $this->updateEmployees();
        $client = new Client();
        $header = Headers::getHeaders();
        $url_base = $this->getUrlBase();
        $command = "employees/update";
        $urlCompleta = $url_base . $command;

        foreach ($employees as $employees) {
          $body = [
                    "id"                 => $employees['ID_REPORT_IT'],
                    "companyId"          => $employees['COMPANY_ID'],
                    "cpf"                => (string) $employees['INSCRICAO_FEDERAL'],
                    "name"               => $employees['NOME'],
                    "companyWorkPlaceId" => $employees['COMPANYWORKPLACEID'],
                    "departmentId"       => $employees['DEPARTMENTID'],
                    "positionId"         => $employees['POSITIONID'],
                    "type"               => $employees['TYPE'],
                ];

            try {
                $res = $client->put($urlCompleta, [
                    'headers' => $header,
                    'json' => $body,

                ]);
                $response = json_decode($res->getBody()->getContents(), true);
                Logs::createLog($command . " - " . $employees['NOME'], "sucess", date_format(now(), 'd-m-Y H:i:s'));
                $this->info("Funcionário atualizado: {$employees['NOME']}");
            } catch (\GuzzleHttp\Exception\ClientException $e) {
                Logs::createLog($command . " - " . $employees['NOME'], "erro", date_format(now(), 'd-m-Y H:i:s'));
                $this->error(
                    "Erro ao atualizar funcionário {$employees['NOME']}: " .
                        $e->getResponse()->getBody()->getContents()
                );
            }
         }
    }
    public function updateEmployees()
    {
        $EmployeesModel = new Employees();
        $employees = $EmployeesModel->getEmployeesUpdate();
        return $employees;
    }   


  
}
