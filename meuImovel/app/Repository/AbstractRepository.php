<?php

namespace App\Repository;

use Illuminate\Database\Eloquent\Model;

class AbstractRepository
{

    /**
     * @var Model
     */
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function selectFilter($fields){
        $this->model = $this->model->addSelect(explode(',',$fields));
    }

    public function addConditions($conditions){
        $conditions = explode(';',$conditions);
        foreach ($conditions as $expression) {
            $exp = explode(':', $expression);
            $this->model = $this->model->where($exp[0], $exp[1], $exp[2]);
        }
    }

    public function getResult(){
        return $this->model;
    }
}
