<?php


namespace App\Console\Commands\Recovery;

use Illuminate\Console\Command;
use App\Http\Headers;
use App\Console\UrlBase;
use GuzzleHttp\Client;
use App\Models\RegisteredEmployees;
use App\Models\Logs;


class RecoverEmployees  extends Command
{

    protected $signature = "report:recoveremployees";

    protected $description = "Comando para recuperar funcionários na API Report It";

    public function getUrlBase()
    {
        $UrlBase = new UrlBase();
        return $UrlBase->getUrlBaseLg();
    }

    public function handle()
    {
        $client = new Client();
        $headers = Headers::getHeaders();
        $url_base = $this->getUrlBase();
        $command = "employees/getAll";
        $urlCompleta = $url_base . $command;



        $res = $client->get($urlCompleta, [
            'headers' => $headers
        ]);

        $response = json_decode($res->getBody()->getContents(), true);



         try {
                RegisteredEmployees::saveEmployees($response);
               
                $this->info('Funcionários recuperados com sucesso!');
            } catch (\GuzzleHttp\Exception\ClientException $e) {
             

                $this->error(
                    "Erro ao salvar funcionário {$response['name']}: " .
                        $e->getResponse()->getBody()->getContents()
                );
            }
    
    
    }
}
