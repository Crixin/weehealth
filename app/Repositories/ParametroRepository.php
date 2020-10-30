<?php

namespace App\Repositories;

use App\Parametro;
use App\Repositories\BaseRepository\BaseRepository;

class ParametroRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new Parametro();
    }
}
