<?php

namespace Modules\Docs\Repositories;

use Modules\Docs\Model\Anexo;
use Modules\Core\Repositories\BaseRepository;

class AnexoRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new Anexo();
    }
}
