<?php

namespace Modules\Portal\Repositories;

use Modules\Portal\Model\Processo;
use Modules\Core\Repositories\BaseRepository;

class ProcessoRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new Processo();
    }
}
