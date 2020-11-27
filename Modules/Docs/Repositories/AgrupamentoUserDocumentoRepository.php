<?php

namespace Modules\Docs\Repositories;

use Modules\Docs\Model\AgrupamentoUserDocumento;
use Modules\Core\Repositories\BaseRepository;

class AgrupamentoUserDocumentoRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new AgrupamentoUserDocumento();
    }
}
