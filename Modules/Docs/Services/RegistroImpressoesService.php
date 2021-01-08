<?php

namespace Modules\Docs\Services;

use Modules\Docs\Repositories\RegistroImpressoesRepository;

class RegistroImpressoesService
{

    protected $registroImpressoesRepository;

    public function __construct(RegistroImpressoesRepository $registroImpressoesRepository)
    {
        $this->registroImpressoesRepository = $registroImpressoesRepository;
    }

    public function create(array $data)
    {
        return $this->registroImpressoesRepository->create($data);
    }
}