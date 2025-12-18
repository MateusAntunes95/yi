<?php

namespace app\services;

use app\services\crawlers\GenericCrawler;
use yii\base\InvalidArgumentException;

class GenericService extends BaseService
{
    private GenericCrawler $crawler;

    public function __construct()
    {
        $this->crawler = $crawler ?? new GenericCrawler();
    }

    /**
     * @param string $url
     * @param array $selectors ['campo' => 'css selector']
     * @return array
     */
    public function extract(string $url, array $selectors): array
    {
        $this->validateUrl($url);
        $this->validateSelectors($selectors);

        return $this->crawler->extract($url, $selectors);
    }

    /**
     * Valida URL (evita SSRF básico)
     */
    private function validateUrl(string $url): void
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new InvalidArgumentException('URL inválida');
        }

        $host = parse_url($url, PHP_URL_HOST);

        if (in_array($host, ['localhost', '127.0.0.1'])) {
            throw new InvalidArgumentException('Host não permitido');
        }
    }

    /**
     * Valida seletores
     */
    private function validateSelectors(array $selectors): void
    {
        if (empty($selectors)) {
            throw new InvalidArgumentException('Seletores não podem ser vazios');
        }

        foreach ($selectors as $key => $selector) {
            if (!is_string($key) || !is_string($selector)) {
                throw new InvalidArgumentException('Formato de seletores inválido');
            }
        }
    }
}
