<?php


namespace App\Console\Commands\Recovery;

use Illuminate\Console\Command;
use App\Http\Headers;
use App\Console\UrlBase;
use GuzzleHttp\Client;
use App\Models\RegisteredDepartments;
use App\Models\Logs;


class RecoverDepartaments  extends Command
{

    protected $signature = "report:recoverdepartments";

    protected $description = "Comando para recuperar departamentos na API Report It";

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
        $command = "departments/getAll";
        $urlCompleta = $url_base . $command;


        try {
            $res = $client->get($urlCompleta, [
                'headers' => $headers
            ]);

            $response = json_decode($res->getBody()->getContents(), true);

            foreach ($response as $departament) {

                RegisteredDepartments::saveDeparments($departament['id'], $departament['companyId'], $departament['code'], $departament['name'], $departament['description'], $departament['insertDateTime']);
                Logs::createLog($command . " - " . $departament['name'], "sucess", date_format(now(), 'd-m-Y H:i:s'));
            }
            $this->info('Departamentos recuperados com sucesso!');

           
        } catch (\GuzzleHttp\Exception\ClientException $e) {

            Logs::createLog($command . " - " . $departament['name'], "erro", date_format(now(), 'd-m-Y H:i:s'));

            $this->error(
                "Erro ao salvar departamento {$departament['name']}: " .
                    $e->getResponse()->getBody()->getContents()
            );
        }

         return 0;
    }
}
