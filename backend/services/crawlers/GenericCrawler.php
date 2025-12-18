<?php

namespace app\services\crawlers;

use Symfony\Component\DomCrawler\Crawler;

class GenericCrawler extends CrawlerBase
{
    /**
     * @param string $url
     * @param array $selectors ['campo' => 'css selector']
     * @return array
     */
    public function extract(string $url, array $selectors): array
    {
        $crawler = $this->fetchCrawler($url);

        if (!$crawler) {
            return [
                'success' => false,
                'error' => 'Erro ao acessar URL'
            ];
        }

        $result = [];

        foreach ($selectors as $key => $selector) {
            try {
                $result[$key] = $this->extractText($crawler, $selector);
            } catch (\Throwable $e) {
                $result[$key] = null;
            }
        }

        return [
            'success' => true,
            'data' => $result
        ];
    }

    /**
     * @param string $url
     * @param array $selectors ['campo' => 'css selector']
     * @return array
     */
    public function extractList(string $url, array $selectors): array
    {
        $crawler = $this->fetchCrawler($url);

        $result = [];

        foreach ($selectors as $key => $selector) {
            $result[$key] = $crawler->filter($selector)->each(fn($n) => trim($n->text()));
        }

        return $result;
    }
}
