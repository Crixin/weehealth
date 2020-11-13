<?php

namespace Modules\Docs\Repositories;

use Modules\Docs\Model\DocumentoFormulario;
use Modules\Core\Repositories\BaseRepository;

class DocumentoFormularioRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new DocumentoFormulario();
    }
}
