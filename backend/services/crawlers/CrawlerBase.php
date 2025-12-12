<?php

namespace app\services\crawlers;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

abstract class CrawlerBase
{
    protected Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'timeout' => 20,
            'headers' => [
                'User-Agent' => 'Mozilla/5.0',
            ],
        ]);
    }

    protected function fetchCrawler(string $url): ?Crawler
    {
        try {
            $response = $this->client->get($url);
            $html = (string) $response->getBody();
            return new Crawler($html);
        } catch (\Throwable $e) {
            \Yii::error($e->getMessage(), __METHOD__);
            return null;
        }
    }

    protected function extractText(Crawler $crawler, string $selector): string
    {
        $node = $crawler->filter($selector);
        return $node->count() > 0 ? trim($node->first()->text()) : '';
    }

    protected function extractMultiple(Crawler $crawler, string $selector): array
    {
        return $crawler->filter($selector)->each(fn($n) => trim($n->text()));
    }
}
