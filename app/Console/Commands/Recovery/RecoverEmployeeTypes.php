<?php


namespace App\Console\Commands\Recovery;

use Illuminate\Console\Command;
use App\Http\Headers;
use App\Console\UrlBase;
use GuzzleHttp\Client;
use App\Models\RegisteredEmployeeTypes;
use App\Models\Logs;


class RecoverEmployeeTypes  extends Command
{

    protected $signature = "report:recoveremployeetypes";

    protected $description = "Comando para recuperar os tipos de funcionários na API Report It";

    public function getUrlBase()
    {
        $UrlBase = new UrlBase();
        return $UrlBase->getUrlBaseLg();
    }

    public function handle()
    {
        $client = new Client();
        $headers = HEaders::getHeaders();
        $url_base = $this->getUrlBase();
        $command = "employeetypes/getAll";
        $urlCompleta = $url_base . $command;


        try {
            $res = $client->get($urlCompleta, [
                'headers' => $headers
            ]);

            $response = json_decode($res->getBody()->getContents(), true);

            foreach ($response as $employeetype) {

               RegisteredEmployeeTypes::saveEmployeeTypes($employeetype['id'], $employeetype['companyId'], $employeetype['title'], $employeetype['insertDateTime']);

                Logs::createLog($command . " - " . $employeetype['title'], "sucess", date_format(now(), 'd-m-Y H:i:s'));
            }
            $this->info('Tipos de funcionários recuperados com sucesso!');
        } catch (\GuzzleHttp\Exception\ClientException $e) {

            Logs::createLog($command . " - " . $employeetype['title'], "erro", date_format(now(), 'd-m-Y H:i:s'));

            $this->error(
                "Erro ao salvar tipo de funcionário {$employeetype['title']}: " .
                    $e->getResponse()->getBody()->getContents()
            );
        }
    }
}
