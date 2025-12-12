<?php

namespace app\services\crawlers;

use Symfony\Component\DomCrawler\Crawler;
use app\dto\ClubDetailDto;
use app\enums\ClubEnum;

class ClubCrawler extends CrawlerBase
{
    public function fetchClubs(): array
    {
        $url = 'https://www.transfermarkt.com/campeonato-brasileiro-serie-a/tabelle/wettbewerb/BRA1/saison_id/2025';

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

    public function fetchClubDetail(ClubEnum $clubEnum): ?ClubDetailDto
    {
        $slug = $clubEnum->value;
        $url = "https://pt.wikipedia.org/wiki/{$slug}";

        $crawler = $this->fetchCrawler($url);
        if (!$crawler) {
            return null;
        }

        $dto = new ClubDetailDto();

        $dto->name       = $this->extractText($crawler, 'tbody tr:contains("Nome") td');
        $dto->nickname   = $this->extractMultiple($crawler, 'tbody tr:contains("Alcunhas") td br');
        $dto->mascot     = $this->extractMultiple($crawler, 'tbody tr:contains("Mascote") td br');
        $dto->founded    = $this->extractText($crawler, 'tbody tr:contains("Fundação") td');
        $dto->stadium    = $this->extractText($crawler, 'tbody tr:contains("Estádio") td a');
        $dto->capacity   = $this->extractText($crawler, 'tbody tr:contains("Capacidade") td');
        $dto->location   = $this->extractText($crawler, 'tbody tr:contains("Localização") td');

        return $dto;
    }
}
