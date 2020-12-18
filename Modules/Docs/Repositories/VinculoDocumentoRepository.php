<?php

namespace Modules\Docs\Repositories;

use Modules\Docs\Model\VinculoDocumento;
use Modules\Core\Repositories\BaseRepository;

class VinculoDocumentoRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new VinculoDocumento();
    }
}
