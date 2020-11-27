<?php

namespace Modules\Docs\Repositories;

use Modules\Docs\Model\Plano;
use Modules\Core\Repositories\BaseRepository;

class PlanoRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new Plano();
    }
}