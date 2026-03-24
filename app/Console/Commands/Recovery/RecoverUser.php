<?php


namespace App\Console\Commands\Recovery;

use Illuminate\Console\Command;
use App\Http\Headers;
use App\Console\UrlBase;
use GuzzleHttp\Client;
use App\Models\Users;
use App\Models\Logs;

class RecoverUser  extends Command
{

    protected $signature = "report:recoveruser";

    protected $description = "Comando para recuperar usuários na API Report It";

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


        $command = "users/getAll/true/false";
        $urlCompleta = $url_base . $command;


        try {
            $res = $client->get($urlCompleta, [
                'headers' => $headers
            ]);

            $response = json_decode($res->getBody()->getContents(), true);

           Users::saveUsers($response);

            $this->info('Cargos recuperados com sucesso!');
        } catch (\GuzzleHttp\Exception\ClientException $e) {

            dd($e);

           // Logs::createLog($command . " - " . $positions['title'], "erro", date_format(now(), 'd-m-Y H:i:s'));

            $this->error(
                "Erro ao salvar cargos : " .
                    $e->getResponse()->getBody()->getContents()
            );
        }
    }
}
