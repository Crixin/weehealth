<?php

namespace Modules\Docs\Repositories;

use Modules\Docs\Model\TipoSetor;
use Modules\Core\Repositories\BaseRepository;

class TipoSetorRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new TipoSetor();
    }
}
