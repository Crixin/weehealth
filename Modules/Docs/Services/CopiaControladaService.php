<?php

namespace Modules\Docs\Services;

use Modules\Docs\Repositories\CopiaControladaRepository;

class CopiaControladaService
{

    protected $copiaControladaRepository;

    public function __construct(CopiaControladaRepository $copiaControladaRepository)
    {
        $this->copiaControladaRepository = $copiaControladaRepository;
    }

    public function create(array $data)
    {
        return $this->copiaControladaRepository->create($data);
    }
}
