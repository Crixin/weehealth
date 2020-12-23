<?php

namespace Modules\Docs\Services;

use Modules\Docs\Repositories\AgrupamentoUserDocumentoRepository;

class AgrupamentoUserDocumentoService
{
    protected $agrupamentoUserDocumentoRepository;
    
    public function __construct(AgrupamentoUserDocumentoRepository $agrupamentoUserDocumentoRepository)
    {
        $this->agrupamentoUserDocumentoRepository = $agrupamentoUserDocumentoRepository;
    }

    public function create(array $dados)
    {
        return $this->agrupamentoUserDocumentoRepository->create($dados);
    }

    public function update(array $dados, int $id)
    {
        return $this->agrupamentoUserDocumentoRepository->update($dados, $id);
    }

    public function delete(array $delete)
    {
        return  $this->agrupamentoUserDocumentoRepository->delete($delete);
    }
}
