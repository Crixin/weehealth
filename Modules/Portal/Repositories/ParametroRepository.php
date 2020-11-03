<?php

namespace Modules\Portal\Repositories;

use Modules\Portal\Model\Parametro;
use App\Repositories\BaseRepository\BaseRepository;

class ParametroRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new Parametro();
    }
}
