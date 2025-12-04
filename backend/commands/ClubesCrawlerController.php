<?php
namespace app\commands;

use yii\console\Controller;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class ClubesCrawlerController extends Controller
{
    public function actionIndex()
    {
        $url = 'https://ge.globo.com/futebol/brasileirao-serie-a/'; // ajustar conforme necessário
        $client = new Client([
            'timeout' => 10,
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (compatible; MeuCrawler/1.0; +http://seusite.com)',
            ],
        ]);
        $response = $client->get($url);
        $html = (string) $response->getBody();

        $crawler = new Crawler($html);

        // Exemplo: supondo que o site liste os clubes em elementos <a> com classe .club-link (ajuste conforme o HTML real)
        $clubes = $crawler->filter('a.club-link')
            ->each(function (Crawler $node) {
                return trim($node->text());
            });

        // Filtra duplicados e limita a 20 clubes
        $clubes = array_unique($clubes);
        $clubes = array_slice($clubes, 0, 20);

        foreach ($clubes as $clube) {
            echo $clube . "\n";
        }
    }
}
