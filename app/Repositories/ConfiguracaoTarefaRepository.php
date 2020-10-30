<?php

namespace App\Repositories;

use App\ConfiguracaoTarefa;
use App\Repositories\BaseRepository\BaseRepository;

class ConfiguracaoTarefaRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new ConfiguracaoTarefa();
    }
}