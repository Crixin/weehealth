<?php

namespace Modules\Docs\Repositories;

use Modules\Docs\Model\EtapaFluxo;
use Modules\Core\Repositories\BaseRepository;

class EtapaFluxoRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new EtapaFluxo();
    }
}
