<?php

namespace App\Repositories;

use App\EmpresaProcesso;
use App\Repositories\BaseRepository\BaseRepository;

class EmpresaProcessoRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new EmpresaProcesso();
    }
}
