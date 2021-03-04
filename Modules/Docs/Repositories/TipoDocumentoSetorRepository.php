<?php

namespace Modules\Docs\Repositories;

use Modules\Docs\Model\TipoDocumentoSetor;
use Modules\Core\Repositories\BaseRepository;

class TipoDocumentoSetorRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new TipoDocumentoSetor();
    }
}
