<?php

namespace Modules\Portal\Repositories;

use Modules\Portal\Model\GrupoUser;
use Modules\Core\Repositories\BaseRepository;

class GrupoUserRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new GrupoUser();
    }
}
