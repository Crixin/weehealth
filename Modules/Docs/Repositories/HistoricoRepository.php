<?php

namespace Modules\Docs\Repositories;

use Modules\Docs\Model\Historico;
use Modules\Core\Repositories\BaseRepository;

class HistoricoRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new Historico();
    }
}
