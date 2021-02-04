<?php

namespace Modules\Docs\Repositories;

use Modules\Docs\Model\Bpmn;
use Modules\Core\Repositories\BaseRepository;

class BpmnRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new Bpmn();
    }
}