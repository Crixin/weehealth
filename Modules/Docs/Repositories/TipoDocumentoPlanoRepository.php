<?php

namespace Modules\Docs\Repositories;

use Modules\Docs\Model\TipoDocumentoPlano;
use Modules\Core\Repositories\BaseRepository;

class TipoDocumentoPlanoRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new TipoDocumentoPlano();
    }
}
