<?php

namespace Modules\Core\Repositories;

use Modules\Core\Repositories\BaseRepository;
use Modules\Core\Model\User;

class UserRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new User();
    }
}
