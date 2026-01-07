<?php

namespace app\services;
 class BaseService
{
    protected $repository;

    public function __construct($repository)
    {
        $this->repository = $repository;
    }

    protected function makeHeader(array $fields): array
    {
        $header = [];
        foreach ($fields as $field) {
            $header[] = [
                'key' => $field,
                'label' => $field
            ];
        }
        return $header;
    }
}
