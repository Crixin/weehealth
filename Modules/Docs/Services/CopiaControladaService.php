<?php

namespace Modules\Docs\Services;

use Modules\Docs\Repositories\CopiaControladaRepository;

class CopiaControladaService
{

    protected $copiaControladaRepository;

    public function __construct()
    {
        $this->copiaControladaRepository = new CopiaControladaRepository();
    }

    public function create(array $data)
    {
        return $this->copiaControladaRepository->create($data);
    }
}
