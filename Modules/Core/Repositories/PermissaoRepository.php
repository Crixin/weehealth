<?php

namespace Modules\Core\Repositories;

use Modules\Core\Model\Permissao;
use Modules\Core\Repositories\BaseRepository;

class PermissaoRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new Permissao();
    }
}
