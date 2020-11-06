<?php

namespace Modules\Portal\Repositories;

use Modules\Portal\Model\EmpresaGrupo;
use Modules\Core\Repositories\BaseRepository;

class EmpresaGrupoRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new EmpresaGrupo();
    }
}
