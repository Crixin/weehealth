<?php

namespace Modules\Docs\Services;

use Illuminate\Support\Facades\DB;
use Modules\Docs\Repositories\DocumentoItemNormaRepository;

class DocumentoItemNormaService
{
    protected $documentoItemNormaRepository;

    public function __construct()
    {
        $this->documentoItemNormaRepository = new DocumentoItemNormaRepository();
    }


    public function store(array $data)
    {
        try {
            DB::beginTransaction();
            
            foreach ($data['item_norma_id'] ?? [] as $key => $value) {
                $this->documentoItemNormaRepository->firstOrCreate(
                    [
                        "item_norma_id" => $value,
                        'documento_id' => $data['documento_id']
                    ]
                );
            }

            DB::commit();
            return ["success" => true];
        } catch (\Throwable $th) {
            DB::rollback();
            return ["success" => false];
        }
    }


    public function delete(array $data)
    {
        try {
            DB::beginTransaction();

            $this->documentoItemNormaRepository->delete($data, 'id');
            
            DB::commit();
            return ["success" => true];
        } catch (\Throwable $th) {
            DB::rollback();
            return ["success" => false];
        }
    }
}
