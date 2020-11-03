<?php

namespace Modules\Portal\Repositories;

use Modules\Portal\Model\Gerenciador;
use App\Repositories\BaseRepository\BaseRepository;

class GerenciadorRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new Gerenciador();
    }
}
