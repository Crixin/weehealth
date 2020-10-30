<?php

namespace App\Repositories;

use App\Dossie;
use App\Repositories\BaseRepository\BaseRepository;

class DossieRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new Dossie();
    }
}
