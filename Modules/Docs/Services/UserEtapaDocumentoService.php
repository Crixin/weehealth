<?php

namespace Modules\Docs\Services;

use Modules\Docs\Repositories\UserEtapaDocumentoRepository;

class UserEtapaDocumentoService
{
    protected $userEtapaDocumentoRepository;

    public function __construct(UserEtapaDocumentoRepository $userEtapaDocumentoRepository)
    {
        $this->userEtapaDocumentoRepository = $userEtapaDocumentoRepository;
    }

    public function create(array $dados)
    {
        return $this->userEtapaDocumentoRepository->create($dados);
    }

    public function update(array $dados, int $id)
    {
        return $this->userEtapaDocumentoRepository->update($dados, $id);
    }

    public function delete (int $delete)
    {
        return $this->userEtapaDocumentoRepository->delete($delete);
    }
}
