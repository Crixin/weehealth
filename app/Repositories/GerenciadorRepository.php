<?php

namespace App\Repositories;

use App\Gerenciador;
use App\Repositories\BaseRepository\BaseRepository;

class GerenciadorRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new Gerenciador();
    }
}
