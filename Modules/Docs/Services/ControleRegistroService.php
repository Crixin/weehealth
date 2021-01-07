<?php

namespace Modules\Docs\Services;

use Modules\Docs\Repositories\ControleRegistroRepository;

class ControleRegistroService
{

    protected $controleRegistroRepository;

    public function __construct(ControleRegistroRepository $controleRegistroRepository)
    {
        $this->controleRegistroRepository = $controleRegistroRepository;
    }

    public function create(array $data)
    {
        return $this->controleRegistroRepository->create($data);
    }
}
