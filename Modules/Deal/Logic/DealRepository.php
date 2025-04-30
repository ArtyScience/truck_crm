<?php

namespace Modules\Deal\Logic;


use Modules\Core\Logic\Abstract\AbstractRepository;
use \Illuminate\Database\Eloquent\Collection;

class DealRepository extends AbstractRepository
{

    public function __construct(){}

    public function all(): Collection
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
