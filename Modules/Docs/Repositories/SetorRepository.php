<?php

namespace Modules\Docs\Repositories;

use Modules\Docs\Model\Setor;
use Modules\Core\Repositories\BaseRepository;

class SetorRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new Setor();
    }
}
