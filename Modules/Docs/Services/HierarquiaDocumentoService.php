<?php

namespace Modules\Docs\Services;

use Modules\Docs\Repositories\HierarquiaDocumentoRepository;

class HierarquiaDocumentoService
{
    protected $hierarquiaDocumentoRepository;


    public function __construct(HierarquiaDocumentoRepository $hierarquiaDocumentoRepository)
    {
        $this->hierarquiaDocumentoRepository = $hierarquiaDocumentoRepository;
    }

    public function create(array $dados)
    {
        return $this->hierarquiaDocumentoRepository->create($dados);
    }

    public function update(array $dados, int $id)
    {
        return $this->hierarquiaDocumentoRepository->update($dados, $id);
    }

    public function delete($delete)
    {
        return $this->hierarquiaDocumentoRepository->delete($delete);
    }

    public function firstOrCreate(array $data)
    {
        return $this->hierarquiaDocumentoRepository->firstOrCreate($data);
    }
}
