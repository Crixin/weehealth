<?php

namespace Modules\Docs\Services;

use App\Classes\Helper;
use App\Services\ValidacaoService;
use Illuminate\Support\Facades\DB;
use Modules\Docs\Model\TipoDocumentoSetor;
use Modules\Docs\Repositories\TipoDocumentoSetorRepository;

class TipoDocumentoSetorService
{

    protected $tipoDocumentoSetorRepository;
    private $rules;

    public function __construct()
    {
        $this->tipoDocumentoSetorRepository = new TipoDocumentoSetorRepository();
        $tipoDocumentoSetor = new TipoDocumentoSetor();
        $this->rules = $tipoDocumentoSetor->rules;
    }

    public function store(array $data)
    {
        try {
            $validacao = new ValidacaoService($this->rules, $data);
            $errors = $validacao->make();
            if ($errors) {
                return ["success" => false, "redirect" => redirect()->back()->withErrors($errors)->withInput()];
            }

            DB::transaction(function () use ($data) {
                $notificacao = $this->tipoDocumentoSetorRepository->firstOrCreate($data);
            });
            return ["success" => true];
        } catch (\Throwable $th) {
            dd($th);
            Helper::setNotify("Erro ao cadastrar a tipo documento setor. " . __("messages.contateSuporteTecnico"), 'danger|close-circle');
            return ["success" => false, "redirect" => redirect()->back()->withInput()];
        }
    }

    public function atualizaUltimoCodigoTipoDocumento($tipoDocumentoId, $setorId)
    {
        $buscaUltimoCodigo = $this->tipoDocumentoSetorRepository->findOneBy(
            [
                ["tipo_documento_id", "=", $tipoDocumentoId],
                ["setor_id", "=", $setorId, "AND"]
            ]
        );
        $request = [
            "ultimo_documento" => $buscaUltimoCodigo->ultimo_documento + 1
        ];
        return $this->tipoDocumentoSetorRepository->update($request, $buscaUltimoCodigo->id);
    }
}
