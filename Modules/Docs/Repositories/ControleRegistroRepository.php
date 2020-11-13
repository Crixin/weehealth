<?php

namespace Modules\Docs\Repositories;

use Modules\Docs\Model\ControleRegistro;
use Modules\Core\Repositories\BaseRepository;

class ControleRegistroRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new ControleRegistro();
    }
}
