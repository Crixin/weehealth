<?php

namespace Modules\Docs\Repositories;

use Modules\Docs\Model\AreaInteresseDocumento;
use Modules\Core\Repositories\BaseRepository;

class AreaInteresseDocumentoRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new AreaInteresseDocumento();
    }
}
