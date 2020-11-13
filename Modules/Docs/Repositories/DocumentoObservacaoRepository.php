<?php

namespace Modules\Docs\Repositories;

use Modules\Docs\Model\DocumentoObservacao;
use Modules\Core\Repositories\BaseRepository;

class DocumentoObservacaoRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new DocumentoObservacao();
    }
}
