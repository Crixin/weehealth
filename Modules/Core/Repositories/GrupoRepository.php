<?php

namespace Modules\Core\Repositories;

use Modules\Core\Model\Grupo;
use Modules\Core\Repositories\BaseRepository;

class GrupoRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new Grupo();
    }

}
