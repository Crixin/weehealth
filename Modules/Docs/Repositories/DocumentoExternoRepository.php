<?php

namespace Modules\Docs\Repositories;

use Modules\Docs\Model\DocumentoExterno;
use Modules\Core\Repositories\BaseRepository;

class DocumentoExternoRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new DocumentoExterno();
    }
}
