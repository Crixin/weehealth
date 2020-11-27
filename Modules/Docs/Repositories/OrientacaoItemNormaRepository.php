<?php

namespace Modules\Docs\Repositories;

use Modules\Docs\Model\OrientacaoItemNorma;
use Modules\Core\Repositories\BaseRepository;

class OrientacaoItemNormaRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new OrientacaoItemNorma();
    }
}
