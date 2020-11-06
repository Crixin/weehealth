<?php

namespace Modules\Core\Repositories;

use Modules\Core\Repositories\BaseRepository;
use Modules\Core\Model\Parametro;

class ParametroRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new Parametro();
    }
}
