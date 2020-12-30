<?php

namespace Modules\Docs\Services;

use Modules\Docs\Repositories\AnexoRepository;

class AnexoService
{
    protected $anexoRepository;

    public function __construct(AnexoRepository $anexoRepository)
    {
        $this->anexoRepository = $anexoRepository;
    }

    public function create(array $dados)
    {
        return $this->anexoRepository->create($dados);
    }

    public function update(array $dados, int $id)
    {
        return $this->anexoRepository->update($dados, $id);
    }

    public function delete($delete)
    {
        return  $this->anexoRepository->delete($delete);
    }
}
