<?php

namespace Modules\Docs\Repositories;

use Modules\Docs\Model\CheckListItemNorma;
use Modules\Core\Repositories\BaseRepository;

class CheckListItemNormaRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new CheckListItemNorma();
    }
}
