<?php

namespace Modules\Docs\Services;

use Modules\Docs\Repositories\ListaPresencaRepository;

class ListaPresencaService
{

    protected $listaPresencaRepository;

    public function __construct()
    {
        $this->listaPresencaRepository = new ListaPresencaRepository();
    }

    public function create(array $data)
    {
        return $this->listaPresencaRepository->create($data);
    }
}
