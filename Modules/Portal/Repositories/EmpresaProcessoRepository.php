<?php

namespace Modules\Portal\Repositories;

use Modules\Portal\Model\EmpresaProcesso;
use App\Repositories\BaseRepository\BaseRepository;

class EmpresaProcessoRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new EmpresaProcesso();
    }
}
