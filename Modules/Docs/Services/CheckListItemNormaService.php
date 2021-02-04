<?php

namespace Modules\Docs\Services;

use Modules\Docs\Repositories\CheckListItemNormaRepository;

class CheckListItemNormaService
{

    protected $checkListItemNormaRepository;

    public function __construct()
    {
        $this->checkListItemNormaRepository = new CheckListItemNormaRepository();
    }

    public function create(array $data)
    {
        return $this->checkListItemNormaRepository->create($data);
    }
}
