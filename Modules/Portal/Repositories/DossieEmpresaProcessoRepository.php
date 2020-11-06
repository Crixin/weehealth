<?php

namespace Modules\Portal\Repositories;

use Modules\Portal\Model\DossieEmpresaProcesso;
use Modules\Core\Repositories\BaseRepository;

class DossieEmpresaProcessoRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new DossieEmpresaProcesso();
    }
}
