<?php

namespace App\Repositories;

use App\Permissao;
use App\Repositories\BaseRepository\BaseRepository;

class PermissaoRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new Permissao();
    }
}
