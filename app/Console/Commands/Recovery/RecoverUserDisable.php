<?php


namespace App\Console\Commands\Recovery;

use Illuminate\Console\Command;
use App\Http\Headers;
use App\Console\UrlBase;
use GuzzleHttp\Client;
use App\Models\Users;
use App\Models\Logs;

class RecoverUserDisable  extends Command
{

    protected $signature = "report:recoveruser";

    protected $description = "Comando para recuperar usuários desabilitados na API Report It";

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


        $command = "users/getAll/false/false";
        $urlCompleta = $url_base . $command;


        try {
            $res = $client->get($urlCompleta, [
                'headers' => $headers
            ]);

            $response = json_decode($res->getBody()->getContents(), true);

            Users::saveUsers($response);

            $this->info('Usuários recuperados com sucesso!');
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            Logs::createLog($command . " - " . "Usuarios não recuperados", "erro", date_format(now(), 'd-m-Y H:i:s'));

            $this->error(
                "Erro ao salvar usuários : " .
                    $e->getResponse()->getBody()->getContents()
            );
        }
    }
}
