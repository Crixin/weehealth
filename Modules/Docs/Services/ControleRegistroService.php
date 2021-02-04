<?php

namespace Modules\Docs\Services;

use Modules\Docs\Repositories\ControleRegistroRepository;

class ControleRegistroService
{

    protected $controleRegistroRepository;

    public function __construct()
    {
        $this->controleRegistroRepository = new ControleRegistroRepository();
    }

    public function create(array $data)
    {
        return $this->controleRegistroRepository->create($data);
    }
}
