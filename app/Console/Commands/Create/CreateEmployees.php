<?php 

namespace App\Console\Commands\Create;  

use GuzzleHttp\Client;
use App\Http\Headers;
use Illuminate\Console\Command;
use App\Console\UrlBase;
use App\Models\Logs;
use App\Models\Employees;


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
            "companyId"   => $employees->COMPANY_ID,
            "cpf"        => (string) $employees->INSCRICAO_FEDERAL,
            "name"        => $employees->NOME,
            "companyWorkPlaceId" => $employees->COMPANYWORKPLACEID,
            "departmentId" => $employees->DEPARTMENTID,
            "positionId" => $employees->POSITIONID,
            "type"        => $employees->TYPE 
        ];

       

        try {
            $res = $client->post($urlCompleta, [
                'headers' => $headers,
                'json'    => $body, 
            ]);

        //    $response = json_decode($res->getBody()->getContents(), true);

            Logs::createLog($command. " - " . $employees->INSCRICAO_FEDERAL, "sucess", date_format(now(), 'd-m-Y H:i:s'));

            $this->info("Funcionário cadastrado : {$employees->INSCRICAO_FEDERAL}");


    } catch (\GuzzleHttp\Exception\ClientException $e) {

            dd($e);

         Logs::createLog($command. " - " . $employees->INSCRICAO_FEDERAL, "erro", date_format(now(), 'd-m-Y H:i:s'));

        $this->error(
            "Erro ao cadastrar funcionário {$employees->INSCRICAO_FEDERAL}: " .
            $e->getResponse()->getBody()->getContents()
        );
    }
}



    }


    




}