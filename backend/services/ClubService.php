<?php

namespace app\services;

use app\repositories\ClubRepository;
use app\services\crawlers\ClubCrawler;

class ClubService
{
    private  $repository;

    public function __construct()
    {
        $this->repository = new ClubRepository();
    }
    
   public function importFromCrawler(): array
{
    $crawler = new ClubCrawler();
    $clubs = $crawler->fetchClubs(); // agora retorna array de Club models
    $results = [];

    foreach ($clubs as $clubModel) {

        // Verifica se já existe no banco pelo nome
        $exists = $this->repository->findBy('name', $clubModel->name);

        if ($exists) {
            $results[] = [
                'name' => $clubModel->name,
                'status' => 'already_exists'
            ];
            continue;
        }

        // Salva o modelo Club
        $saved = false;
        try {
            $saved = $this->repository->save($clubModel); // agora recebe ActiveRecord
        } catch (\Throwable $e) {
            \Yii::error("Erro ao salvar clube {$clubModel->name}: " . $e->getMessage(), __METHOD__);
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

}
