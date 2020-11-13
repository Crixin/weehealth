<?php

namespace Modules\Docs\Repositories;

use Modules\Docs\Model\TipoDocumento;
use Modules\Core\Repositories\BaseRepository;

class TipoDocumentoRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new TipoDocumento();
    }
}
