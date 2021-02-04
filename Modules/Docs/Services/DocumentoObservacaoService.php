<?php

namespace Modules\Docs\Services;

use Modules\Docs\Repositories\DocumentoObservacaoRepository;

class DocumentoObservacaoService
{

    protected $documentoObservacaoRepository;

    public function __construct()
    {
        $this->documentoObservacaoRepository = new DocumentoObservacaoRepository();
    }

    public function create(array $data)
    {
        return $this->documentoObservacaoRepository->create($data);
    }
}
