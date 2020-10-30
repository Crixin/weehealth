<?php

namespace App\Repositories;

use App\Perfil;
use App\Repositories\BaseRepository\BaseRepository;

class PerfilRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new Perfil();
    }
}
