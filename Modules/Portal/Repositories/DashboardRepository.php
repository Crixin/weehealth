<?php

namespace Modules\Portal\Repositories;

use Modules\Portal\Model\Dashboard;
use App\Repositories\BaseRepository\BaseRepository;

class DashboardRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new Dashboard();
    }
}
