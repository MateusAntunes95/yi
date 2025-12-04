<?php

namespace services;

use repositories\ClubRepository;
use services\crawlers\ClubCrawler;

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
        $names = $crawler->fetchClubs();
        $results = [];

        foreach ($names as $name) {

            $exists = $this->repository->findBy('name', $name);

            if ($exists) {
                $results[] = [
                    'name' => $name,
                    'status' => 'already_exists'
                ];
                continue;
            }

            $club = $this->repository->save($name);

            $results[] = [
                'name' => $name,
                'status' => $club ? 'created' : 'failed'
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
