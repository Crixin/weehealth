<?php

namespace Modules\Core\Repositories;

use Modules\Core\Model\Log;
use Modules\Core\Repositories\BaseRepository;

class LogRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new Log();
    }
}
