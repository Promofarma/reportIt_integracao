<?php

namespace App\Console\Commands\Create;  

use GuzzleHttp\Client;
use App\Http\BodyToken;
use App\Http\Headers;
use Illuminate\Console\Command;
use App\Console\UrlBase;
use App\Models\CompanyWorkplaces;


class CreateEmployeeTypes  extends Command
{

    protected $signature = "report:createemployeetypes";

    protected $description = "Comando para criar tipos de funcionários na API Report It";


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


        $command = "employeetypes/add";
        $urlCompleta = $url_base . $command;

        $typeEmployee = [
            [
                "id" => 1,
                "title" => "Pessoa Física"
            ],
            [
                "id" => 2,
                "title" => "Pessoa Jurídica"
            ]

       ];

        foreach ($typeEmployee as $type) {

            $body = [
                "companyId"   => 1,
                "title" => $type['title']
            ];

            try {
                $res = $client->post($urlCompleta, [
                    'headers' => $headers,
                    'body'    => json_encode($body),
                ]);

                $response = $res->getBody()->getContents();
                $data = json_decode($response, true);

                $this->info($data['title']);
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }
    }

    }
       
}
