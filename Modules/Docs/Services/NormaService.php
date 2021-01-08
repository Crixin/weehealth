<?php

namespace Modules\Docs\Services;

use Modules\Docs\Repositories\NormaRepository;

class NormaService
{

    protected $normaRepository;

    public function __construct(NormaRepository $normaRepository)
    {
        $this->normaRepository = $normaRepository;
    }

    public function create(array $data)
    {
        return $this->normaRepository->create($data);
    }
}
