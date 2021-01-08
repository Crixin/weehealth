<?php

namespace Modules\Docs\Services;

use Modules\Docs\Repositories\FluxoRepository;

class FluxoService
{

    protected $fluxoRepository;

    public function __construct(FluxoRepository $fluxoRepository)
    {
        $this->fluxoRepository = $fluxoRepository;
    }

    public function create(array $data)
    {
        return $this->fluxoRepository->create($data);
    }
}
