<?php

namespace app\commands;

use yii\console\Controller;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class ClubesCrawlerController extends Controller
{
    public function actionIndex()
    {
        $url = 'https://www.transfermarkt.com/campeonato-brasileiro-serie-a/tabelle/wettbewerb/BRA1/saison_id/2025';

        $client = new Client([
            'timeout' => 20,
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (compatible; MeuCrawler/1.0)',
            ],
        ]);

        try {
            $response = $client->get($url);
            $html = (string) $response->getBody();

            $crawler = new Crawler($html);

            // seleciona todas as linhas da classificação
            $rows = $crawler->filter('table.items tbody tr');

            $this->stdout("Linhas encontradas: " . $rows->count() . PHP_EOL);

            $clubes = [];

            $rows->each(function (Crawler $row) use (&$clubes) {
                $nomeCell = $row->filter('td.hauptlink a');

                if ($nomeCell->count() > 0) {
                    $clubes[] = trim($nomeCell->eq(0)->text());
                }
            });

            // mantém apenas os 20 primeiros clubes
            $clubes = array_slice($clubes, 0, 20);

            if (empty($clubes)) {
                $this->stderr("Nenhum clube encontrado no HTML final." . PHP_EOL);
                return;
            }

            foreach ($clubes as $i => $clube) {
                $this->stdout(($i + 1) . ". " . $clube . PHP_EOL);
            }

        } catch (\Throwable $e) {
            $this->stderr("Erro ao acessar $url: " . $e->getMessage() . PHP_EOL);
        }
    }
}
