<?php

namespace Modules\Docs\Repositories;

use Modules\Docs\Model\UsuarioExtra;
use Modules\Core\Repositories\BaseRepository;

class UsuarioExtraRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new UsuarioExtra();
    }
}
