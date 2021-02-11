<?php

namespace Modules\Docs\Repositories;

use Modules\Docs\Model\HistoricoDocumento;
use Modules\Core\Repositories\BaseRepository;

class HistoricoDocumentoRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new HistoricoDocumento();
    }
}
