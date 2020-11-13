<?php

namespace Modules\Portal\Repositories;

use Modules\Portal\Model\UserDashboard;
use Modules\Core\Repositories\BaseRepository;

class UserDashboardRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new UserDashboard();
    }
}
