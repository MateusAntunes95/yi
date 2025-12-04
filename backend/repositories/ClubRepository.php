<?php

namespace repositories;

use app\models\Club;

class ClubRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(new Club());
    }

    // Exemplo usando o novo método
    public function findByName(string $name): ?Club
    {
        return $this->findBy('name', $name);
    }
}
