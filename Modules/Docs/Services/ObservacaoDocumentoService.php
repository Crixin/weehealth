<?php

namespace Modules\Docs\Services;

use App\Classes\Helper;
use App\Services\ValidacaoService;
use Illuminate\Support\Facades\{DB, Auth};
use Modules\Docs\Model\ObservacaoDocumento;
use Modules\Docs\Repositories\ObservacaoDocumentoRepository;

class ObservacaoDocumentoService
{

    protected $observacaoDocumentoRepository;
    private $rules;

    public function __construct()
    {
        $plano = new ObservacaoDocumento();
        $this->rules = $plano->rules;
        $this->observacaoDocumentoRepository = new ObservacaoDocumentoRepository();
    }

    public function store(array $data)
    {
        try {
            DB::beginTransaction();

            $data = [
                'observacao' => $data['observacao_documento'],
                'documento_id' => $data['documento_id'],
                'user_id' => Auth::id(),
            ];

            if (!$this->observacaoDocumentoRepository->create($data)) {
                throw new \Exception("Falha ao gravar a observação no documento");
            }

            DB::commit();
            return ["success" => true];
        } catch (\Throwable $th) {
            DB::rollback();
            return ["success" => false];
        }
    }
}
