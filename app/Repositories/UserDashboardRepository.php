<?php

namespace App\Repositories;

use App\UserDashboard;
use App\Repositories\BaseRepository\BaseRepository;

class UserDashboardRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new UserDashboard();
    }


    public function user()
    {
        return $this->belongsTo('App/User');
    }


    public function dashboard()
    {
        return $this->belongsTo('App/Dashboard');
    }
}
