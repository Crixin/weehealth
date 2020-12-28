<?php

namespace Modules\Docs\Services;

use Modules\Docs\Repositories\DocumentoItemNormaRepository;

class DocumentoItemNormaService
{
    protected $documentoItemNormaRepository;

    public function __construct(DocumentoItemNormaRepository $documentoItemNormaRepository)
    {
        $this->documentoItemNormaRepository = $documentoItemNormaRepository;
    }

    public function create(array $dados)
    {

        return $this->documentoItemNormaRepository->create($dados);
    }

    public function update(array $dados, int $id)
    {
        return $this->documentoItemNormaRepository->update($dados, $id);
    }

    public function delete(array $delete)
    {
        return $this->documentoItemNormaRepository->delete($delete);
    }
}
