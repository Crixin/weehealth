<?php

namespace Modules\Portal\Repositories;

use Modules\Portal\Model\EmpresaProcesso;
use Modules\Core\Repositories\BaseRepository;

class EmpresaProcessoRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new EmpresaProcesso();
    }
}
