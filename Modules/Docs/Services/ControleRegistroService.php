<?php

namespace Modules\Docs\Services;

use App\Classes\Helper;
use App\Services\ValidacaoService;
use Illuminate\Support\Facades\DB;
use Modules\Docs\Model\ControleRegistro;
use Modules\Docs\Repositories\ControleRegistroRepository;

class ControleRegistroService
{

    protected $controleRegistroRepository;
    protected $rules;
    public function __construct()
    {
        $controleRegistro  = new ControleRegistro();
        $this->rules = $controleRegistro->rules;
        $this->controleRegistroRepository = new ControleRegistroRepository();
    }

    public function store(array $data)
    {
        try {
            $insert = [
                'codigo'          => $data['codigo'],
                'descricao'       => $data['titulo'],
                'responsavel'     => $data['setor_id'],
                'meio'            => $data['meio_distribuicao_id'],
                'armazenamento'   => $data['local_armazenamento_id'],
                'protecao'        => $data['protecao_id'],
                'recuperacao'     => $data['recuperacao_id'],
                'nivelAcesso'     => $data['nivel_acesso_id'],
                'retencaoLocal'   => $data['tempo_retencao_local_id'],
                'retencaoDeposito' => $data['tempo_retencao_deposito_id'],
                'disposicao'       => $data['disposicao_id'],

            ];
            $validacao = new ValidacaoService($this->rules, $insert);
            $errors = $validacao->make();
            if ($errors) {
                return ["success" => false, "redirect" => redirect()->back()->withErrors($errors)->withInput()];
            }

            DB::transaction(function () use ($data) {
                $notificacao = $this->controleRegistroRepository->create($data);
            });
            return ["success" => true];
        } catch (\Throwable $th) {
            Helper::setNotify("Erro ao cadastrar o controle de registro. " . __("messages.contateSuporteTecnico"), 'danger|close-circle');
            return ["success" => false, "redirect" => redirect()->back()->withInput()];
        }
    }

    public function update(array $data)
    {
        try {
            $this->rules['codigo'] .= "," . $data['id'];
            $update = [
                'codigo'          => $data['codigo'],
                'descricao'       => $data['titulo'],
                'responsavel'     => $data['setor_id'],
                'meio'            => $data['meio_distribuicao_id'],
                'armazenamento'   => $data['local_armazenamento_id'],
                'protecao'        => $data['protecao_id'],
                'recuperacao'     => $data['recuperacao_id'],
                'nivelAcesso'     => $data['nivel_acesso_id'],
                'retencaoLocal'   => $data['tempo_retencao_local_id'],
                'retencaoDeposito' => $data['tempo_retencao_deposito_id'],
                'disposicao'       => $data['disposicao_id'],
            ];

            $validacao = new ValidacaoService($this->rules, $update);

            $errors = $validacao->make();

            if ($errors) {
                return ["success" => false, "redirect" => redirect()->back()->withErrors($errors)->withInput()];
            }
            DB::transaction(function () use ($data) {
                $notificacao = $this->controleRegistroRepository->update($data, $data['id']);
            });

            return ["success" => true];
        } catch (\Throwable $th) {
            dd($th);
            Helper::setNotify("Erro ao atualizar o controle de registro. " . __("messages.contateSuporteTecnico"), 'danger|close-circle');
            return ["success" => false, "redirect" => redirect()->back()->withInput()];
        }
    }

    public function delete($id)
    {
        $this->planoRepository->delete($id);
    }
}
