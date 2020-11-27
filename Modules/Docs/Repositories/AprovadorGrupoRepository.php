<?php

namespace Modules\Docs\Repositories;

use Modules\Docs\Model\AprovadorGrupo;
use Modules\Core\Repositories\BaseRepository;

class AprovadorGrupoRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new AprovadorGrupo();
    }
}
