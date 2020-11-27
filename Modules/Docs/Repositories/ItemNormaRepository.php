<?php

namespace Modules\Docs\Repositories;

use Modules\Docs\Model\ItemNorma;
use Modules\Core\Repositories\BaseRepository;

class ItemNormaRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new ItemNorma();
    }
}
