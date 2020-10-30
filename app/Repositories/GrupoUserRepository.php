<?php

namespace App\Repositories;

use App\GrupoUser;
use App\Repositories\BaseRepository\BaseRepository;

class GrupoUserRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new GrupoUser();
    }
}
