<?php

namespace Modules\Docs\Repositories;

use Modules\Docs\Model\ListaPresenca;
use Modules\Core\Repositories\BaseRepository;

class ListaPresencaRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new ListaPresenca();
    }
}
