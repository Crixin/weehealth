<?php

namespace App\Repositories;

use App\Cidade;
use App\Repositories\BaseRepository\BaseRepository;

class CidadeRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new Cidade();
    }
}
