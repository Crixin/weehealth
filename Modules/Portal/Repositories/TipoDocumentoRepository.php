<?php

namespace Modules\Portal\Repositories;

use Modules\Portal\Model\TipoDocumento;
use App\Repositories\BaseRepository\BaseRepository;

class TipoDocumentoRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new TipoDocumento();
    }
}
