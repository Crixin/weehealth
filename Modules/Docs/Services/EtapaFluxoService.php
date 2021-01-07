<?php

namespace Modules\Docs\Services;

use Modules\Docs\Repositories\EtapaFluxoRepository;

class EtapaFluxoService
{

    protected $etapaFluxoRepository;

    public function __construct(EtapaFluxoRepository $etapaFluxoRepository)
    {
        $this->etapaFluxoRepository = $etapaFluxoRepository;
    }

    public function create(array $data)
    {
        return $this->etapaFluxoRepository->create($data);
    }
}
