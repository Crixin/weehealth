<?php

namespace Modules\Docs\Services;

use Illuminate\Support\Facades\DB;
use Modules\Docs\Repositories\VinculoDocumentoRepository;

class VinculoDocumentoService
{
    protected $vinculoDocumentoRepository;


    public function __construct()
    {
        $this->vinculoDocumentoRepository = new VinculoDocumentoRepository();
    }

    public function store(array $dados)
    {
        try {
            DB::beginTransaction();
            foreach ($dados['documento_vinculado_id'] as $key => $value) {

                $this->vinculoDocumentoRepository->firstOrCreate(
                    [
                        "documento_id" => $dados['documento_id'],
                        "documento_vinculado_id"  => $value['documento_vinculado_id']
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

            $this->vinculoDocumentoRepository->delete($data, 'id');
            
            DB::commit();
            return ["success" => true];
        } catch (\Throwable $th) {
            DB::rollback();
            return ["success" => false];
        }
    }
}
