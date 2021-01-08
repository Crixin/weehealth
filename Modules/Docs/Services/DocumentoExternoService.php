<?php

namespace Modules\Docs\Services;

use Modules\Docs\Repositories\DocumentoExternoRepository;

class DocumentoExternoService
{

    protected $documentoExternoRepository;

    public function __construct(DocumentoExternoRepository $documentoExternoRepository)
    {
        $this->documentoExternoRepository = $documentoExternoRepository;
    }

    public function create(array $data)
    {
        return $this->documentoExternoRepository->create($data);
    }
}
