<?php

namespace Modules\Docs\Repositories;

use Modules\Docs\Model\DocumentoPai;
use Modules\Core\Repositories\BaseRepository;

class DocumentoPaiRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new DocumentoPai();
    }
}
