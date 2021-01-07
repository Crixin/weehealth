<?php

namespace Modules\Docs\Services;

use Modules\Docs\Repositories\PlanoRepository;

class PlanoService
{

    protected $planoRepository;

    public function __construct(PlanoRepository $planoRepository)
    {
        $this->planoRepository = $planoRepository;
    }

    public function create(array $data)
    {
        return $this->planoRepository->create($data);
    }
}
