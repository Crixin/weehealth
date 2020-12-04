<?php

namespace Modules\Docs\Repositories;

use Modules\Docs\Model\OpcoesControleRegistros;
use Modules\Core\Repositories\BaseRepository;

class OpcaoControleRegistroRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new OpcoesControleRegistros();
    }
}
