<?php

namespace Modules\Portal\Repositories;

use Modules\Portal\Model\UserDashboard;
use App\Repositories\BaseRepository\BaseRepository;

class UserDashboardRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new UserDashboard();
    }
}
