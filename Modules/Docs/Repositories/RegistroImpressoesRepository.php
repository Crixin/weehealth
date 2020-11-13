<?php

namespace Modules\Docs\Repositories;

use Modules\Docs\Model\RegistroImpressoes;
use Modules\Core\Repositories\BaseRepository;

class RegistroImpressoesRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new RegistroImpressoes();
    }
}
