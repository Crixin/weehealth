<?php

namespace Modules\Portal\Repositories;

use Modules\Portal\Model\EmpresaUser;
use App\Repositories\BaseRepository\BaseRepository;

class EmpresaUserRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new EmpresaUser();
    }
}
