<?php

namespace Modules\Portal\Repositories;

use Modules\Portal\Model\Dossie;
use Modules\Core\Repositories\BaseRepository;

class DossieRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new Dossie();
    }
}
