<?php

namespace Modules\Docs\Services;

use App\Classes\Helper;
use App\Services\ValidacaoService;
use Illuminate\Support\Facades\DB;
use Modules\Docs\Model\DocumentoExterno;
use Modules\Docs\Repositories\DocumentoExternoRepository;

class DocumentoExternoService
{

    protected $documentoExternoRepository;
    protected $rules;

    public function __construct()
    {
        $documentoExterno = new DocumentoExterno();
        $this->rules = $documentoExterno->rules;
        $this->documentoExternoRepository = new DocumentoExternoRepository();
    }

    public function store(array $data)
    {
        try {
            $insert = [
                "setor"                   => $data['setor_id'],
                "fornecedor"              => $data['empresa_id'],
                "versao"                  => $data['revisao'],
                "validade"                => $data['validade']
            ];
            $validacao = new ValidacaoService($this->rules, $insert);
            $errors = $validacao->make();
            if ($errors) {
                return ["success" => false, "redirect" => redirect()->back()->withErrors($errors)->withInput()];
            }

            DB::transaction(function () use ($data) {
                $documentoExterno = $this->documentoExternoRepository->create($data);
            });
            return ["success" => true];
        } catch (\Throwable $th) {
            Helper::setNotify("Erro ao cadastrar o documento externo. " . __("messages.contateSuporteTecnico"), 'danger|close-circle');
            return ["success" => false, "redirect" => redirect()->back()->withInput()];
        }
    }

    public function update(array $data)
    {
        try {
            $insert = [
                "setor"                   => $data['setor_id'],
                "fornecedor"              => $data['empresa_id'],
                "versao"                  => $data['revisao'],
                "validade"                => $data['validade']
            ];

            $validacao = new ValidacaoService($this->rules, $insert);

            $errors = $validacao->make();

            if ($errors) {
                return ["success" => false, "redirect" => redirect()->back()->withErrors($errors)->withInput()];
            }
            DB::transaction(function () use ($data) {
                $documentoExterno = $this->documentoExternoRepository->update($data, $data['id']);
            });

            return ["success" => true];
        } catch (\Throwable $th) {
            Helper::setNotify("Erro ao atualizar a documento externo. " . __("messages.contateSuporteTecnico"), 'danger|close-circle');
            return ["success" => false, "redirect" => redirect()->back()->withInput()];
        }
    }

    public function delete($id)
    {
        $this->planoRepository->delete($id);
    }
}
