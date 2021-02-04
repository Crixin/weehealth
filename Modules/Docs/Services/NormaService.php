<?php

namespace Modules\Docs\Services;

use Modules\Docs\Repositories\NormaRepository;

class NormaService
{

    protected $normaRepository;

    public function __construct()
    {
        $this->normaRepository = new NormaRepository();
    }

    public function create(array $data)
    {
        return $this->normaRepository->create($data);
    }
}
