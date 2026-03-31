<?php

namespace App\Console\Commands\Update;

use App\Console\UrlBase;
use App\Http\Headers;
use App\Models\Employees;
use App\Models\Logs;
use App\Models\Positions;
use App\Models\RegisteredPositions;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Soap\Url;


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
        $employeesOutdated = $this->updateEmployees()->sortBy('NOME');
        $client = new Client();
        $header = Headers::getHeaders();
        $url_base = $this->getUrlBase();
        $command = "employees/update";
        $urlCompleta = $url_base . $command;

     
       

        foreach ($employeesOutdated as $employees) {
          $body = [
                    "id"                 => $employees->ID_REPORT_IT,
                    "companyId"          => $employees->COMPANY_ID,
                    "cpf"                => (string) $employees->INSCRICAO_FEDERAL,
                    "name"               => $employees->NOME,
                    "socialName"         => $employees->NOME_SOCIAL,
                    "companyWorkPlaceId" => $employees->COMPANYWORKPLACEID,
                    "departmentId"       => $employees->DEPARTMENTID,
                    "positionId"         => $employees->POSITIONID,
                    "type"               => $employees->TYPE,
                    "birthDate"          => Carbon::parse($employees->DATA_NASCIMENTO)->format('d-m-Y'),
                    "birthCountry"       => $employees->NACIONALIDADE,
                    "raceColor"          => $employees->ETNIA_DESCRICAO,
                    "admissionDate"      => Carbon::parse($employees->DATA_ADMISSAO)->format('d-m-Y'),  
                    "gender"             => $employees->SEXO,
                    "email"              => $employees->E_MAIL,
                    "civilState"         => $employees->ESTADO_CIVIL,
                    "educationLevel"     => $employees->GRAU_INSTRUCAO,
                    "cep"                => $employees->CEP,
                    "placeAddress"       => $employees->ENDERECO,
                    "placeNumber"        => $employees->NUMERO,    
                    "placeComplement"    => $employees->COMPLEMENTO,
                    "placeDistrict"      => $employees->BAIRRO, 
                    "placeState"         => $employees->ESTADO,
                    "placeCity"          => $employees->CIDADE,
                    "phone"              => $employees->TELEFONE,
                    "internalRegistrationId" => $employees->matricula,
                ];

            try {
                $res = $client->put($urlCompleta, [
                    'headers' => $header,
                    'json' => $body,

                ]);
                $response = json_decode($res->getBody()->getContents(), true);

             

                Logs::createLog($command . " - " . $employees->NOME, "sucess", date_format(now(), 'd-m-Y H:i:s'));
                $this->info("Funcionário atualizado: {$employees->NOME}");
            } catch (\GuzzleHttp\Exception\ClientException $e) {
             

                Logs::createLog($command . " - " . $employees->NOME, "erro", date_format(now(), 'd-m-Y H:i:s'));
                $this->error(
                    "Erro ao atualizar funcionário {$employees->NOME}: " .
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
