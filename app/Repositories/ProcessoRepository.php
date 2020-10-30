<?php

namespace App\Repositories;

use App\Processo;
use App\Repositories\BaseRepository\BaseRepository;

class ProcessoRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new Processo();
    }
}
