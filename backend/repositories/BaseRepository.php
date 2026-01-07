<?php

namespace app\repositories;

use yii\db\ActiveRecord;

abstract class BaseRepository
{
    protected ActiveRecord $model;

    public function __construct(ActiveRecord $model)
    {
        $this->model = $model;
    }

    public function findById(int $id): ?ActiveRecord
    {
        return $this->model::findOne($id);
    }

   public function findAll(array $fields = []): array
    {
        $query = $this->model::find();
        if (!empty($fields)) {
            $query->select($fields);
        }
        return $query->all();
    }
    public function save(ActiveRecord $model): bool
    {
        return $model->save();
    }

    public function delete(ActiveRecord $model): bool
    {
        return (bool) $model->delete();
    }

    public function findBy(string $field, mixed $value): ?ActiveRecord
    {
        return $this->model::find()
            ->where([$field => $value])
            ->one();
    }
}
