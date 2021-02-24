<?php

namespace Modules\Docs\Services;

use App\Classes\Helper;
use App\Services\ValidacaoService;
use Illuminate\Support\Facades\DB;
use Modules\Docs\Model\Bpmn;
use Modules\Docs\Repositories\BpmnRepository;

class BpmnService
{
    protected $bpmnRepository;
    private $rules;

    public function __construct()
    {
        $this->bpmnRepository = new BpmnRepository();

        $bpmn = new Bpmn();
        $this->rules = $bpmn->rules;
    }

    public function create(array $dados)
    {
        $insert = [
            "nome"              => $dados['nome'],
            "versao"            => $dados['versao'],
            "arquivo"           => $dados['arquivo']
        ];

        $validacao = new ValidacaoService($this->rules, $insert);
        $errors = $validacao->make();

        if ($errors) {
            return ["success" => false, "redirect" => redirect()->back()->withErrors($errors)->withInput()];
        }

        DB::beginTransaction();
        try {
            $this->bpmnRepository->create($dados);
            DB::commit();
            return ["success" => true];
        } catch (\Throwable $th) {
            DB::rollback();
            Helper::setNotify("Erro ao cadastrar o BPMN. " . __("messages.contateSuporteTecnico"), 'danger|close-circle');
            return ["success" => false, "redirect" => redirect()->back()->withInput()];
        }
    }

    public function update(array $dados)
    {
        $this->rules['nome'] .= "," . $dados['id'];

        $insert = [
            "nome"              => $dados['nome'],
            "versao"            => $dados['versao'],
            "arquivo"           => $dados['arquivo']
        ];

        $validacao = new ValidacaoService($this->rules, $insert);
        $errors = $validacao->make();

        if ($errors) {
            return ["success" => false, "redirect" => redirect()->back()->withErrors($errors)->withInput()];
        }

        DB::beginTransaction();
        try {
            $this->bpmnRepository->update($dados, $dados['id']);
            DB::commit();
            return ["success" => true];
        } catch (\Throwable $th) {
            dd($th);
            DB::rollback();
            Helper::setNotify("Erro ao atualizar o BPMN. " . __("messages.contateSuporteTecnico"), 'danger|close-circle');
            return ["success" => false, "redirect" => redirect()->back()->withInput()];
        }
    }

    public function delete($delete)
    {
        DB::beginTransaction();
        try {
            $this->bpmnRepository->delete($delete);
            DB::commit();
            return ["success" => true];
        } catch (\Throwable $th) {
            DB::rollback();
            return ["success" => false];
        }
    }
}
