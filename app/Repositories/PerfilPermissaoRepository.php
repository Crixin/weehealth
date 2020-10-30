<?php

namespace App\Repositories;

use App\PerfilPermissao;
use App\Repositories\BaseRepository\BaseRepository;

class PerfilPermissaoRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new PerfilPermissao();
    }
}
