<?php

namespace app\services\crawlers;

use Symfony\Component\DomCrawler\Crawler;
use app\dto\ClubDetailDto;
use app\enums\ClubEnum;
use Yii;

class ClubCrawler extends CrawlerBase
{
    /**
     * @return array
     */
    public function fetchClubs(): array
    {
        $url = Yii::$app->params['crawler']['transfermarkt']['serie_a_2025'];

        $crawler = $this->fetchCrawler($url);
        if (!$crawler) {
            return [];
        }

        $clubs = [];
        $rows = $crawler->filter('table.items tbody tr');

        $rows->each(function (Crawler $row) use (&$clubs) {
            $nameCell = $row->filter('td.hauptlink a');
            if ($nameCell->count() > 0) {
                $name = trim($nameCell->eq(0)->text());

                $clubModel = new \app\models\Club();
                $clubModel->name = $name;
                $clubs[] = $clubModel;
            }
        });

        return array_slice($clubs, 0, 20);
    }

    /**
     * @param ClubEnum $clubEnum
     * @return ClubDetailDto|null
     */
    public function fetchClubDetail(ClubEnum $clubEnum): ?ClubDetailDto
    {
        $urlTemplate = Yii::$app->params['crawler']['wikipedia']['club_detail'];
        $url = str_replace('{slug}', $clubEnum->value, $urlTemplate);

        $crawler = $this->fetchCrawler($url);
        if (!$crawler) {
            return null;
        }

        $dto = new ClubDetailDto();

        $dto->name = $this->extractText($crawler, 'tbody tr:contains("Nome") td:nth-child(2)');
        $dto->nickname = $this->extractMultiple($crawler, 'tbody tr:contains("Alcunhas") td:nth-child(2) br');
        $dto->mascot = $this->extractMultiple($crawler, 'tbody tr:contains("Mascote") td:nth-child(2) br');

        $founded = $this->extractText($crawler, 'tbody tr:contains("Fundação") td:nth-child(2)');
        $dto->founded = trim(explode(';', $founded)[0]);

        $dto->stadium = $this->extractText($crawler, 'tbody tr:contains("Estádio") td:nth-child(2) a');
        $dto->capacity = $this->extractText($crawler, 'tbody tr:contains("Capacidade") td:nth-child(2)');
        $dto->location = $this->extractText($crawler, 'tbody tr:contains("Localização") td:nth-child(2)');

        return $dto;
    }
}
