<?php

namespace services;

abstract class BaseService
{
    protected $repository;

    public function __construct($repository)
    {
        $this->repository = $repository;
    }
}
