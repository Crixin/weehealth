<?php

namespace Modules\Portal\Repositories;

use Modules\Portal\Model\ConfiguracaoTarefa;
use Modules\Core\Repositories\BaseRepository;

class ConfiguracaoTarefaRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new ConfiguracaoTarefa();
    }
}
