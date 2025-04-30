<?php

namespace Modules\Core\Logic\Abstract;

use Illuminate\Database\Eloquent\Model;

abstract class AbstractRepository
{
    protected object $model;

    /**
     * @param mixed $model
     */
    public function setModel($model): void
    {
        $this->model = $model;
    }

    public function all(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model->all();
    }

    public function get(int $limit = 25)
    {
        return $this->model->limit($limit)->get();
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $item = $this->model->find($id);
        if ($item) {
            $item->update($data);
            return $item;
        }
        return false;
    }

    public function delete($id)
    {
        $item = $this->model->find($id);
        if ($item) {
            $item->delete();
            return true;
        }
        return false;
    }
}
