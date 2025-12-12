<?php

namespace app\dto;

class ClubDetailDto
{
    public string $name;
    public array $nickname = [];
    public array $mascot = [];
    public string $founded;
    public string $stadium;
    public string $capacity;
    public string $location;
}
