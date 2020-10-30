<?php

namespace App\Repositories;

use App\DossieEmpresaProcesso;
use App\Repositories\BaseRepository\BaseRepository;

class DossieEmpresaProcessoRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new DossieEmpresaProcesso();
    }
}
