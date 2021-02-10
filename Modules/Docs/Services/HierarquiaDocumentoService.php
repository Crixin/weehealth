<?php

namespace Modules\Docs\Services;

use Modules\Docs\Repositories\HierarquiaDocumentoRepository;
use Illuminate\Support\Facades\DB;

class HierarquiaDocumentoService
{
    protected $hierarquiaDocumentoRepository;

    public function __construct()
    {
        $this->hierarquiaDocumentoRepository = new HierarquiaDocumentoRepository();
    }


    public function store(array $dados)
    {
        try {
            DB::beginTransaction();
            
            foreach ($dados['hierarquia_documento'] as $key => $value) {
                $this->hierarquiaDocumentoRepository->firstOrCreate(
                    [
                        "documento_id" => $dados['documento_id'],
                        "documento_pai_id"  => $value['documento_pai_id']
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

            $this->hierarquiaDocumentoRepository->delete($data, 'id');
            
            DB::commit();
            return ["success" => true];
        } catch (\Throwable $th) {
            DB::rollback();
            return ["success" => false];
        }
    }
}
