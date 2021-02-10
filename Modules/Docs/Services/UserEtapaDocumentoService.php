<?php

namespace Modules\Docs\Services;

use Illuminate\Support\Facades\DB;
use Modules\Docs\Repositories\UserEtapaDocumentoRepository;

class UserEtapaDocumentoService
{
    protected $userEtapaDocumentoRepository;

    public function __construct()
    {
        $this->userEtapaDocumentoRepository = new UserEtapaDocumentoRepository();
    }

    public function store(array $data)
    {
        try {
            DB::beginTransaction();
            foreach ($data['grupo_user_etapa'] ?? [] as $grupoUserEtapa) {
                $this->userEtapaDocumentoRepository->firstOrCreate(
                    [
                        "grupo_id" => $grupoUserEtapa['grupo_id'],
                        "user_id" => $grupoUserEtapa['user_id'],
                        "etapa_fluxo_id" => $grupoUserEtapa['etapa_fluxo_id'],
                        "documento_revisao" => $data['documento_revisao'],
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

            $this->userEtapaDocumentoRepository->delete($data, 'id');
            
            DB::commit();
            return ["success" => true];
        } catch (\Throwable $th) {
            DB::rollback();
            return ["success" => false];
        }
    }
}
