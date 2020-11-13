<?php

namespace Modules\Docs\Repositories;

use Modules\Docs\Model\OpcoesControleRegistros;
use Modules\Core\Repositories\BaseRepository;

class OpcoesControleRegistrosRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new OpcoesControleRegistros();
    }
}
