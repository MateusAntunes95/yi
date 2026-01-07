<?php

namespace app\services;

use app\dto\ClubDetailDto;
use app\enums\ClubEnum;
use app\repositories\ClubRepository;
use app\services\crawlers\ClubCrawler;
use RuntimeException;

class ClubService extends BaseService
{
    private ClubCrawler $clubCrawler;

    /**
     * @return void
     */
    public function __construct()
    {
        parent::__construct(new ClubRepository());
        $this->clubCrawler = new ClubCrawler();
    }

    /**
     * @return array
     */
    public function importFromCrawler(): array
    {
        $clubs = $this->clubCrawler->fetchClubs();
        $results = [];

        foreach ($clubs as $clubModel) {

            $exists = $this->repository->findBy('name', $clubModel->name);

            if ($exists) {
                $results[] = [
                    'name' => $clubModel->name,
                    'status' => 'already_exists'
                ];
                continue;
            }

            $saved = false;
            try {
                $saved = $this->repository->save($clubModel);
            } catch (\Throwable $e) {
                \Yii::error(
                    "Erro ao salvar clube {$clubModel->name}: {$e->getMessage()}",
                    __METHOD__
                );
            }

            $results[] = [
                'name' => $clubModel->name,
                'status' => $saved ? 'created' : 'failed'
            ];
        }

        return $results;
    }

    /**
     * @param string|null $name
     * @return array
     */
    public function list(?string $name): array
    {
        if (!empty($name)) {
            $club = $this->repository->findBy('name', $name);
            return [
                'success' => true,
                'header' => [],
                'clubs' => $club ? [$club] : [],
            ];
        }

        $fields = ['name', 'founded', 'stadium'];

        $header = $this->makeHeader($fields);

        $clubs = $this->repository->findAll(['name']);

        return [
            'success' => true,
            'header' => $header,
            'clubs' => $clubs,
        ];
    }

    /**
     * @param ClubEnum $clubEnum
     * @return ClubDetailDto
     */
    public function getClubField(string $slug, string $field): array
    {
        $club = $this->repository->findByName($slug);
        if (!$club) {
            throw new RuntimeException("Clube não encontrado: {$slug}");
        }

        $detail = json_decode($club->detail ?? '{}', true) ?? [];

        if (!empty($detail[$field])) {
            return (array) $detail[$field];
        }

        $enum = constant("app\\enums\\ClubEnum::{$slug}");
        $dto = $this->clubCrawler->fetchClubDetail($enum);

        $detail = $dto->toArray();

        $club->detail = json_encode(
            $detail,
            JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT
        );
        $club->save(false);

        return (array) ($detail[$field] ?? '?');
    }
}
