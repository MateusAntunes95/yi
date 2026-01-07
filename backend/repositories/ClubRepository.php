<?php

namespace app\repositories;

use app\models\Club;

class ClubRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(new Club());
    }

    public function findByName(string $name): ?Club
    {
        return $this->findBy('name', $name);
    }

    public function findDetailDtoByName(string $name): \app\dto\ClubDetailDto
    {
        $club = $this->findByName($name);

        $detailArray = $club && $club->detail
            ? json_decode($club->detail, true) ?? []
            : [];

        $dto = new \app\dto\ClubDetailDto();

        foreach ($dto as $key => $_) {
            $dto->$key = $detailArray[$key] ?? '?';
        }

        return $dto;
    }
}
