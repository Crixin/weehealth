<?php

namespace Modules\Docs\Repositories;

use Modules\Docs\Model\Documento;
use Modules\Core\Repositories\BaseRepository;

class DocumentoRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new Documento();
    }
}
