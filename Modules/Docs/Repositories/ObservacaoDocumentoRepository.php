<?php

namespace Modules\Docs\Repositories;

use Modules\Docs\Model\ObservacaoDocumento;
use Modules\Core\Repositories\BaseRepository;

class ObservacaoDocumentoRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new ObservacaoDocumento();
    }
}
