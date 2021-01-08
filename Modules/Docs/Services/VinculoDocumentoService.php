<?php

namespace Modules\Docs\Services;

use Modules\Docs\Repositories\VinculoDocumentoRepository;

class VinculoDocumentoService
{
    protected $vinculoDocumentoRepository;


    public function __construct(VinculoDocumentoRepository $vinculoDocumentoRepository)
    {
        $this->vinculoDocumentoRepository = $vinculoDocumentoRepository;
    }

    public function create(array $dados)
    {
        return $this->vinculoDocumentoRepository->create($dados);
    }

    public function update(array $data, int $id)
    {
        return $this->vinculoDocumentoRepository->update($data, $id);
    }

    public function delete($delete)
    {
        return $this->vinculoDocumentoRepository->delete($delete);
    }

    public function firstOrCreate(array $data)
    {
        return $this->vinculoDocumentoRepository->firstOrCreate($data);
    }
}
