<?php

namespace Modules\Docs\Services;

use App\Classes\Helper;
use App\Services\ValidacaoService;
use Illuminate\Support\Facades\DB;
use Modules\Docs\Model\OpcoesControleRegistros;
use Modules\Docs\Repositories\OpcaoControleRegistroRepository;

class OpcaoControleRegistroService
{

    protected $opcaoControleRegistroRepository;
    private $rules;

    public function __construct()
    {
        $this->opcaoControleRegistroRepository = new OpcaoControleRegistroRepository();
        $plano = new OpcoesControleRegistros();
        $this->rules = $plano->rules;
    }

    public function store(array $data)
    {
        try {
            $insert = [
                "descricao"     => $data['descricao'],
                "campo_id"      => $data['campo_id'],
            ];
            $validacao = new ValidacaoService($this->rules, $insert);
            $errors = $validacao->make();
            if ($errors) {
                return ["success" => false, "redirect" => redirect()->back()->withErrors($errors)->withInput()];
            }

            DB::transaction(function () use ($data) {
                $notificacao = $this->opcaoControleRegistroRepository->create($data);
            });
            return ["success" => true];
        } catch (\Throwable $th) {
            Helper::setNotify("Erro ao cadastrar a opção de controle. " . __("messages.contateSuporteTecnico"), 'danger|close-circle');
            return ["success" => false, "redirect" => redirect()->back()->withInput()];
        }
    }

    public function update(array $data)
    {
        try {
            $this->rules['descricao'] .= "," . $data['id'];

            $insert = [
                "descricao"     => $data['descricao'],
                "campo_id"      => $data['campo_id'],
            ];

            $validacao = new ValidacaoService($this->rules, $insert);

            $errors = $validacao->make();

            if ($errors) {
                return ["success" => false, "redirect" => redirect()->back()->withErrors($errors)->withInput()];
            }
            DB::transaction(function () use ($data) {
                $notificacao = $this->opcaoControleRegistroRepository->update($data, $data['id']);
            });

            return ["success" => true];
        } catch (\Throwable $th) {
            Helper::setNotify("Erro ao atualizar a opção de controle. " . __("messages.contateSuporteTecnico"), 'danger|close-circle');
            return ["success" => false, "redirect" => redirect()->back()->withInput()];
        }
    }

    public function delete($id)
    {
        $this->planoRepository->delete($id);
    }
}
