<?php

namespace App\Repositories;

use App\Empresa;
use App\Repositories\BaseRepository\BaseRepository;

class EmpresaRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new Empresa();
    }
}
