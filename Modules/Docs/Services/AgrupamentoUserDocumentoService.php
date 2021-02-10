<?php

namespace Modules\Docs\Services;

use Illuminate\Support\Facades\DB;
use Modules\Docs\Repositories\AgrupamentoUserDocumentoRepository;

class AgrupamentoUserDocumentoService
{
    protected $agrupamentoUserDocumentoRepository;

    
    public function __construct()
    {
        $this->agrupamentoUserDocumentoRepository = new AgrupamentoUserDocumentoRepository();
    }

    
    public function store(array $data)
    {
        try {
            DB::beginTransaction();
            
            foreach ($data['grupo_and_user'] ?? [] as $key => $value) {
                $this->agrupamentoUserDocumentoRepository->firstOrCreate(
                    [
                        "documento_id" => $data['documento_id'],
                        "user_id"  => $value['user_id'],
                        "grupo_id" => $value['grupo_id'],
                        'tipo' => $data['tipo']
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

            $this->agrupamentoUserDocumentoRepository->delete($data, 'id');
            
            DB::commit();
            return ["success" => true];
        } catch (\Throwable $th) {
            DB::rollback();
            return ["success" => false];
        }
    }
}
