<?php

namespace Modules\Portal\Repositories;

use Modules\Portal\Model\Grupo;
use App\Repositories\BaseRepository\BaseRepository;

class GrupoRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new Grupo();
    }
}
