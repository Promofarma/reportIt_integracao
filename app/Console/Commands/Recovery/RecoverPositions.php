<?php


namespace App\Console\Commands\Recovery;

use Illuminate\Console\Command;
use App\Http\Headers;
use App\Console\UrlBase;
use GuzzleHttp\Client;
use App\Models\RegisteredDepartments;
use App\Models\RegisteredPositions;
use App\Models\Logs;

class RecoverPositions  extends Command
{

    protected $signature = "report:recoverpositions";

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


        $command = "positions/getAll";
        $urlCompleta = $url_base . $command;


        try {
            $res = $client->get($urlCompleta, [
                'headers' => $headers
            ]);

            $response = json_decode($res->getBody()->getContents(), true);


            foreach ($response as $positions) {

                RegisteredPositions::savePositions($positions['id'], $positions['companyId'], $positions['code'], $positions['title'], $positions['description'], $positions['insertDateTime']);
            }


            $this->info('Cargos recuperados com sucesso!');
        } catch (\GuzzleHttp\Exception\ClientException $e) {

            Logs::createLog($command . " - " . $positions['title'], "erro", date_format(now(), 'd-m-Y H:i:s'));

            $this->error(
                "Erro ao enviar departamento {$positions['title']}: " .
                    $e->getResponse()->getBody()->getContents()
            );
        }
    }
}
