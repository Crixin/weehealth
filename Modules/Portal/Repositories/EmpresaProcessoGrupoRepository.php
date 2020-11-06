<?php

namespace Modules\Portal\Repositories;

use Modules\Portal\Model\EmpresaProcessoGrupo;
use Modules\Core\Repositories\BaseRepository;

class EmpresaProcessoGrupoRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new EmpresaProcessoGrupo();
    }
}
