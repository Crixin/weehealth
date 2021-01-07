<?php

namespace Modules\Docs\Services;

use Modules\Docs\Repositories\UsuarioExtraRepository;

class UsuarioExtraService
{

    protected $usuarioExtraRepository;

    public function __construct(UsuarioExtraRepository $usuarioExtraRepository)
    {
        $this->usuarioExtraRepository = $usuarioExtraRepository;
    }

    public function create(array $data)
    {
        return $this->usuarioExtraRepository->create($data);
    }
}
