<?php

namespace Modules\Portal\Repositories;

use Modules\Portal\Model\Tarefa;
use Modules\Core\Repositories\BaseRepository;

class TarefaRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new Tarefa();
    }
}
