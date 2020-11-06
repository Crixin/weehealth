<?php

namespace Modules\Core\Repositories;

abstract class BaseRepository
{
   /**
   * Model class for repo.
   *
   * @var string
   */
    protected $model;

    /**
    * @param  int  $id do registro
    * @param  array $with. Array de ligações dos models, caso já queira trazer direto da consulta
    */

    public function find($id, array $with = [])
    {
        return $this->model::with($with)->find($id);
    }

    /**
    * @param  array $with. Array de ligações dos models, caso já queira trazer direto da consulta
    * @param  array $orderBy. Array de arrays para ordernação. Ex: [['descricao', 'ASC'], ['nome', 'DESC']]
    */
    public function findAll(array $with = [], array $orderBy = [])
    {
        $model = $this->model::with($with);

        if (count($orderBy)) {
            foreach ($orderBy as $order) {
                $model = $model->orderBy($order[0], $order[1]);
            }
        }
        return $model->get();
    }


    public function create(array $data)
    {
        return $this->model::create($data);
    }


    public function update(array $data, $id)
    {
        return $this->model::find($id)->update($data);
    }


    public function delete($_delete)
    {
        return is_array($_delete) ? $this->model::where($_delete)->delete() : $this->model::find($_delete)->delete();
    }


    public function findBy(array $where, array $with = [], array $orderBy = [], $limit = null, $offset = null)
    {
        $model = $this->model;

        $model = $model::with($with);

        foreach ($where as $value) {
            switch ($value[3] ?? '') {
                case "AND":
                case "and":
                    $model = $model->where($value[0], $value[1], $value[2]);
                    break;

                case "OR":
                case "or":
                    $model = $model->orWhere($value[0], $value[1], $value[2]);
                    break;

                case "IN":
                case "in":
                    $model = $model->whereIn($value[0], $value[2]);
                    break;

                default:
                    $model = $model->where($value[0], $value[1], $value[2]);
                    break;
            }
        }

        if (!is_null($limit)) {
            $model = $model->limit((int) $limit);
        }

        if (!is_null($offset)) {
            $model = $model->offset((int) $offset);
        }
        
        $model = $model->get();

        if (count($orderBy)) {
            foreach ($orderBy as $order) {
                if (strtoupper($order[1] ?? "") == "DESC") {
                    $model = $model->sortByDesc($order[0]);
                } else {
                    $model = $model->sortBy($order[0]);
                }
            }
        }
        return $model;
    }


    public function findOneBy(array $where, array $with = [])
    {
        return $this->findBy($where, $with)->first();
    }
}
