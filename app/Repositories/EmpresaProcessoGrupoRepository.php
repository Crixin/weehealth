<?php

namespace App\Repositories;

use App\EmpresaProcessoGrupo;
use App\Repositories\BaseRepository\BaseRepository;

class EmpresaProcessoGrupoRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new EmpresaProcessoGrupo();
    }
}
