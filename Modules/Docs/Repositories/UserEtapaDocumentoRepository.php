<?php

namespace Modules\Docs\Repositories;

use Modules\Docs\Model\UserEtapaDocumento;
use Modules\Core\Repositories\BaseRepository;

class UserEtapaDocumentoRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new UserEtapaDocumento();
    }
}
