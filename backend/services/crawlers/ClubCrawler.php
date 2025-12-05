<?php

namespace app\services\crawlers;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class ClubCrawler
{
    public function fetchClubs(): array
{
    $url = 'https://www.transfermarkt.com/campeonato-brasileiro-serie-a/tabelle/wettbewerb/BRA1/saison_id/2025';

    $client = new Client([
        'timeout' => 20,
        'headers' => ['User-Agent' => 'Mozilla/5.0'],
    ]);

    try {
        $response = $client->get($url);
        $html = (string) $response->getBody();

        $crawler = new Crawler($html);

        $clubs = [];
        $rows = $crawler->filter('table.items tbody tr');

        $rows->each(function (Crawler $row) use (&$clubs) {
            $nameCell = $row->filter('td.hauptlink a');
            if ($nameCell->count() > 0) {
                $name = trim($nameCell->eq(0)->text());

                // Criar modelo Club
                $clubModel = new \app\models\Club();
                $clubModel->name = $name;

                $clubs[] = $clubModel;
            }
        });

        // Retorna no máximo 20 clubes
        return array_slice($clubs, 0, 20);
    } catch (\Throwable $e) {
        \Yii::error($e->getMessage(), __METHOD__);
        return [];
    }
}

}
