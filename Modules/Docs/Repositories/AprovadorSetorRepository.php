<?php

namespace Modules\Docs\Repositories;

use Modules\Docs\Model\AprovadorSetor;
use Modules\Core\Repositories\BaseRepository;

class AprovadorSetorRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new AprovadorSetor();
    }
}
