<?php

namespace app\services;

use app\dto\ClubDetailDto;
use app\enums\ClubEnum;
use app\repositories\ClubRepository;
use app\services\crawlers\ClubCrawler;
use RuntimeException;

class ClubService 
{
    private ClubRepository $repository;
    private ClubCrawler $clubCrawler;

    public function __construct()
    {
        $this->repository  = new ClubRepository();
        $this->clubCrawler = new ClubCrawler();
    }

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

    public function list(?string $name): array
    {
        if (!empty($name)) {
            $club = $this->repository->findBy('name', $name);
            return $club ? [$club] : [];
        }

        return $this->repository->findAll();
    }

    public function getDetail(ClubEnum $clubEnum): ClubDetailDto
    {
        // Localiza pelo nome do enum (ex: FLAMENGO, PALMEIRAS etc.)
        $club = $this->repository->findByName($clubEnum->name);
        if (!$club) {
            throw new RuntimeException("Clube não encontrado no banco: {$clubEnum->name}");
        }

        // Crawler obtém detalhes usando o value do enum (slug da Wiki)
        $detailDto = $this->clubCrawler->fetchClubDetail($clubEnum);
        if (!$detailDto) {
            throw new RuntimeException("Crawler não conseguiu obter detalhes do clube: {$clubEnum->name}");
        }

        // Salva no banco como JSON
        $club->detail = json_encode($detailDto);

        if (!$club->save()) {
            $errors = json_encode($club->errors);
            throw new RuntimeException("Erro ao salvar detalhes do clube: {$errors}");
        }

        return $detailDto;
    }
}
