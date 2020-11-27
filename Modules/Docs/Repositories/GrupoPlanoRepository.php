<?php

namespace Modules\Docs\Repositories;

use Modules\Docs\Model\GrupoPlano;
use Modules\Core\Repositories\BaseRepository;

class GrupoPlanoRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new GrupoPlano();
    }
}
