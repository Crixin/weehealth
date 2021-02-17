<?php

namespace Modules\Docs\Services;

use Modules\Docs\Repositories\EtapaFluxoRepository;

class EtapaFluxoService
{

    protected $etapaFluxoRepository;

    public function __construct()
    {
        $this->etapaFluxoRepository = new EtapaFluxoRepository();
    }

    public function store(array $data)
    {
        return $this->etapaFluxoRepository->create($data);
    }

    public function update(array $data, $id)
    {
        return $this->etapaFluxoRepository->update($data, $id);
    }

    public function firstOrCreate($data)
    {
        return $this->etapaFluxoRepository->firstOrCreate($data);
    }
}
