<?php

namespace Modules\Portal\Repositories;

use Modules\Portal\Model\EmpresaGrupo;
use App\Repositories\BaseRepository\BaseRepository;

class EmpresaGrupoRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new EmpresaGrupo();
    }
}
