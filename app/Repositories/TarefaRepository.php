<?php

namespace App\Repositories;

use App\Tarefa;
use App\Repositories\BaseRepository\BaseRepository;

class TarefaRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new Tarefa();
    }
}