<?php

namespace Modules\Docs\Services;

use Modules\Docs\Repositories\AgrupamentoUserDocumentoRepository;

class AgrupamentoUserDocumentoService
{
    protected $agrupamentoUserDocumentoRepository;

    public function __construct()
    {
        $this->agrupamentoUserDocumentoRepository = new AgrupamentoUserDocumentoRepository();
    }

    public function create(array $dados)
    {
        return $this->agrupamentoUserDocumentoRepository->create($dados);
    }

    public function update(array $dados, int $id)
    {
        return $this->agrupamentoUserDocumentoRepository->update($dados, $id);
    }

    public function delete($delete, $column = '')
    {
        return  $this->agrupamentoUserDocumentoRepository->delete($delete, $column);
    }

    public function firstOrCreate(array $data)
    {
        return $this->agrupamentoUserDocumentoRepository->firstOrCreate($data);
    }
}
