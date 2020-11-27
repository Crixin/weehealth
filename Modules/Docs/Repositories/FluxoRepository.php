<?php

namespace Modules\Docs\Repositories;

use Modules\Docs\Model\Fluxo;
use Modules\Core\Repositories\BaseRepository;

class FluxoRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new Fluxo();
    }
}
