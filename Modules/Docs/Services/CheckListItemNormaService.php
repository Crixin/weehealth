<?php

namespace Modules\Docs\Services;

use Modules\Docs\Repositories\CheckListItemNormaRepository;

class CheckListItemNormaService
{

    protected $checkListItemNormaRepository;

    public function __construct(CheckListItemNormaRepository $checkListItemNormaRepository)
    {
        $this->checkListItemNormaRepository = $checkListItemNormaRepository;
    }

    public function create(array $data)
    {
        return $this->checkListItemNormaRepository->create($data);
    }
}
