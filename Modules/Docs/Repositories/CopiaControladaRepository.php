<?php

namespace Modules\Docs\Repositories;

use Modules\Docs\Model\CopiaControlada;
use Modules\Core\Repositories\BaseRepository;

class CopiaControladaRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new CopiaControlada();
    }
}
