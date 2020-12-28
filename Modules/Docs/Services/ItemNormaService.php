<?php

namespace Modules\Docs\Services;

use Modules\Docs\Repositories\ItemNormaRepository;

class ItemNormaService
{

    protected $itemNormaRepository;

    public function __construct(ItemNormaRepository $itemNormaRepository)
    {
        $this->itemNormaRepository = $itemNormaRepository;
    }

    public function create(array $data)
    {
        return $this->itemNormaRepository->create($data);
    }
}
