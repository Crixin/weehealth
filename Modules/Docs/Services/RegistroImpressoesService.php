<?php

namespace Modules\Docs\Services;

use Modules\Docs\Repositories\RegistroImpressoesRepository;

class RegistroImpressoesService
{

    protected $registroImpressoesRepository;

    public function __construct()
    {
        $this->registroImpressoesRepository = new RegistroImpressoesRepository();
    }

    public function create(array $data)
    {
        return $this->registroImpressoesRepository->create($data);
    }
}