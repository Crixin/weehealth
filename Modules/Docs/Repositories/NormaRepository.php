<?php

namespace Modules\Docs\Repositories;

use Modules\Docs\Model\Norma;
use Modules\Core\Repositories\BaseRepository;

class NormaRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new Norma();
    }
}
