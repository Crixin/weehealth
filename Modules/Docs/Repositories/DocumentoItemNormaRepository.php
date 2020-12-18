<?php

namespace Modules\Docs\Repositories;

use Modules\Docs\Model\DocumentoItemNorma;
use Modules\Core\Repositories\BaseRepository;

class DocumentoItemNormaRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new DocumentoItemNorma();
    }
}
