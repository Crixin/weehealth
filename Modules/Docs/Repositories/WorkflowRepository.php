<?php

namespace Modules\Docs\Repositories;

use Modules\Docs\Model\Workflow;
use Modules\Core\Repositories\BaseRepository;

class WorkflowRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new Workflow();
    }
}
