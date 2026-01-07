<?php

namespace app\dto;

class ClubDetailDto
{
    public string $name = '';
    public array $nickname = [];
    public array $mascot = [];
    public string $founded = '';
    public string $stadium = '';
    public string $capacity = '';
    public string $location = '';

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'nickname' => $this->nickname,
            'mascot' => $this->mascot,
            'founded' => $this->founded,
            'stadium' => $this->stadium,
            'capacity' => $this->capacity,
            'location' => $this->location,
        ];
    }
}
