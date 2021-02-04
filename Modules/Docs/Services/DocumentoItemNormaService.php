<?php

namespace Modules\Docs\Services;

use Modules\Docs\Repositories\DocumentoItemNormaRepository;

class DocumentoItemNormaService
{
    protected $documentoItemNormaRepository;

    public function __construct()
    {
        $this->documentoItemNormaRepository = new DocumentoItemNormaRepository();
    }

    public function create(array $dados)
    {

        return $this->documentoItemNormaRepository->create($dados);
    }

    public function update(array $dados, int $id)
    {
        return $this->documentoItemNormaRepository->update($dados, $id);
    }

    public function delete($delete, $column = '')
    {
        return $this->documentoItemNormaRepository->delete($delete, $column);
    }
}
