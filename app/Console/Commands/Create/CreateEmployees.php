<?php

namespace App\Console\Commands\Create;

use App\Console\UrlBase;
use App\Http\Headers;
use App\Models\Employees;
use App\Models\Logs;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Console\Command;


class CreateEmployees extends Command
{

    protected $signature = "report:createemployees";

    protected $description = "Comando para criar os funcionários na API Report It";


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


        $command = "employees/add";
        $urlCompleta = $url_base . $command;


        $EmployeesModel = new Employees();
        $employees = $EmployeesModel->getEmployees();




        foreach ($employees as $employees) {

            $body = [
                "companyId"              => $employees->COMPANY_ID,
                "companyWorkPlaceId"     => $employees->COMPANYWORKPLACEID,
                "departmentId"           => $employees->DEPARTMENTID,
                "positionId"             => $employees->POSITIONID,
                "type"                   => $employees->TYPE,
                "cpf"                    => (string) $employees->INSCRICAO_FEDERAL,
                "name"                   => $employees->NOME,
                "socialName"             => $employees->NOME_SOCIAL,
                "birthDate"              => Carbon::parse($employees->DATA_NASCIMENTO)->format('d-m-Y'),
                "birthCountry"           => $employees->NACIONALIDADE,
                "raceColor"              => $employees->ETNIA_DESCRICAO,
                "admissionDate"          => Carbon::parse($employees->DATA_ADMISSAO)->format('d-m-Y'),
                "gender"                 => $employees->SEXO,
                "email"                  => $employees->E_MAIL,
                "civilState"             => $employees->ESTADO_CIVIL,
                "educationLevel"         => $employees->GRAU_INSTRUCAO,
                "cep"                    => $employees->CEP,
                "placeAddress"           => $employees->ENDERECO,
                "placeNumber"            => $employees->NUMERO,
                "placeComplement"        => $employees->COMPLEMENTO,
                "placeDistrict"          => $employees->BAIRRO,
                "placeState"             => $employees->ESTADO,
                "placeCity"              => $employees->CIDADE,
                "phone"                  => $employees->TELEFONE,
                "internalRegistrationId" => $employees->matricula,
            ];


            try {
                $res = $client->post($urlCompleta, [
                    'headers' => $headers,
                    'json'    => $body,
                ]);

                //    $response = json_decode($res->getBody()->getContents(), true);

                Logs::createLog($command . " - " . $employees->INSCRICAO_FEDERAL, "sucess", date_format(now(), 'd-m-Y H:i:s'));

                $this->info("Funcionário cadastrado : {$employees->INSCRICAO_FEDERAL}");
            } catch (\GuzzleHttp\Exception\ClientException $e) {

                Logs::createLog($command . " - " . $employees->INSCRICAO_FEDERAL, "erro", date_format(now(), 'd-m-Y H:i:s'));

                $this->error(
                    "Erro ao cadastrar funcionário {$employees->INSCRICAO_FEDERAL}: " .
                        $e->getResponse()->getBody()->getContents()
                );
            }
        }
    }
}
