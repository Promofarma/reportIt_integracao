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
        $headers = HEaders::getHeaders();
        $url_base = $this->getUrlBase();
        $command = "employees/getAll";
        $urlCompleta = $url_base . $command;


        try {
            $res = $client->get($urlCompleta, [
                'headers' => $headers
            ]);

            $response = json_decode($res->getBody()->getContents(), true);

       

            foreach ($response as $employees) {

                RegisteredEmployees::saveEmployees($employees['id'], $employees['companyId'],  $employees['departmentId'], $employees['positionId'], $employees['type'], $employees['companyWorkPlaceId'], $employees['cpf'], $employees['name'], $employees['insertDateTime'], $employees['updateDateTime']);
                Logs::createLog($command . " - " . $employees['name'], "sucess", date_format(now(), 'd-m-Y H:i:s'));
            }
            $this->info('Funcionários recuperados com sucesso!');
        } catch (\GuzzleHttp\Exception\ClientException $e) {

            

            Logs::createLog($command . " - " . $employees['name'], "erro", date_format(now(), 'd-m-Y H:i:s'));

            $this->error(
                "Erro ao salvar funcionário {$employees['name']}: " .
                    $e->getResponse()->getBody()->getContents()
            );
        }
    }
}
