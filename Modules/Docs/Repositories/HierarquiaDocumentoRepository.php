<?php

namespace Modules\Docs\Repositories;

use Modules\Docs\Model\HierarquiaDocumento;
use Modules\Core\Repositories\BaseRepository;

class HierarquiaDocumentoRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new HierarquiaDocumento();
    }
}
