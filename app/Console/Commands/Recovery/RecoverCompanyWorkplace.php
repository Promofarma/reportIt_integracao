<?php


namespace App\Console\Commands\Recovery;

use Illuminate\Console\Command;
use App\Http\Headers;
use App\Console\UrlBase;
use GuzzleHttp\Client;
use App\Models\RegisteredCompanyWorkplace;
use App\Models\Logs;


class RecoverCompanyWorkplace  extends Command
{

    protected $signature = "report:recovercompanyworkplace";

    protected $description = "Comando para recuperar a empresa na API Report It";

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
        $command = "companyworkplaces/getAll";
        $urlCompleta = $url_base . $command;


        try {
            $res = $client->get($urlCompleta, [
                'headers' => $headers
            ]);

            $response = json_decode($res->getBody()->getContents(), true);

            foreach ($response as $company) {


                try {
                    RegisteredCompanyWorkplace::saveCompany($company['id'], $company['companyId'], $company['name'], $company['document']);

                    Logs::createLog($command . " - " . $company['name'], "sucess", date_format(now(), 'd-m-Y H:i:s'));

                    $this->info('Estabelecimentos recuperadas com sucesso!');
                } catch (\GuzzleHttp\Exception\ClientException $th) {
                    Logs::createLog($command . " - " . $company['name'], "erro", date_format(now(), 'd-m-Y H:i:s'));

                    $this->error(
                        "Erro ao salvar Estabelecimentos {$company['name']}: " .
                            $th->getResponse()->getBody()->getContents()
                    );
                }
            }
        } catch (\GuzzleHttp\Exception\ClientException $e) {

            $this->error(
                "Erro na requisição " .
                    $e->getResponse()->getBody()->getContents()
            );
        }
    }
}
