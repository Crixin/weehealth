<?php

namespace Modules\Core\Repositories;

use Modules\Core\Repositories\BaseRepository;
use Modules\Core\Model\Perfil;

class PerfilRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new Perfil();
    }
}
