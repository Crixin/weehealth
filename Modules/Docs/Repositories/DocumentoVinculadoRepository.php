<?php

namespace Modules\Docs\Repositories;

use Modules\Docs\Model\DocumentoVinculado;
use Modules\Core\Repositories\BaseRepository;

class DocumentoVinculadoRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new DocumentoVinculado();
    }
}
