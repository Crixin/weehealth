<?php

namespace Modules\Docs\Services;

use Modules\Docs\Repositories\ItemNormaRepository;

class ItemNormaService
{

    protected $itemNormaRepository;

    public function __construct()
    {
        $this->itemNormaRepository = new ItemNormaRepository();
    }

    public function create(array $data)
    {
        return $this->itemNormaRepository->create($data);
    }
}
