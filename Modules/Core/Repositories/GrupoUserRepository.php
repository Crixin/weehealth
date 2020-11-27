<?php

namespace Modules\Core\Repositories;

use Modules\Core\Model\GrupoUser;
use Modules\Core\Repositories\BaseRepository;

class GrupoUserRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new GrupoUser();
    }
}
