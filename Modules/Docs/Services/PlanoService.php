<?php

namespace Modules\Docs\Services;

use App\Classes\Helper;
use App\Services\ValidacaoService;
use Illuminate\Support\Facades\DB;
use Modules\Docs\Model\Plano;
use Modules\Docs\Repositories\PlanoRepository;

class PlanoService
{

    protected $planoRepository;
    private $rules;

    public function __construct()
    {
        $this->planoRepository = new PlanoRepository();
        $plano = new Plano();
        $this->rules = $plano->rules;
    }

    public function store(array $data)
    {
        try {
            $insert = [
                "nome"     => $data['nome'],
                "status"   => $data['ativo'],
            ];

            $validacao = new ValidacaoService($this->rules, $insert);
            $errors = $validacao->make();
            if ($errors) {
                return ["success" => false, "redirect" => redirect()->back()->withErrors($errors)->withInput()];
            }

            DB::transaction(function () use ($data) {
                $notificacao = $this->planoRepository->create($data);
            });
            return ["success" => true];
        } catch (\Throwable $th) {
            Helper::setNotify("Erro ao cadastrar a plano. " . __("messages.contateSuporteTecnico"), 'danger|close-circle');
            return ["success" => false, "redirect" => redirect()->back()->withInput()];
        }
    }

    public function update(array $montaRequest)
    {
        try {
            $this->rules['nome'] .= "," . $montaRequest['id'];

            $insert = [
                "nome"     => $montaRequest['nome'],
                "status"   => $montaRequest['ativo']
            ];

            $validacao = new ValidacaoService($this->rules, $insert);

            $errors = $validacao->make();

            if ($errors) {
                return ["success" => false, "redirect" => redirect()->back()->withErrors($errors)->withInput()];
            }
            DB::transaction(function () use ($montaRequest) {
                $notificacao = $this->planoRepository->update($montaRequest, $montaRequest['id']);
            });

            return ["success" => true];
        } catch (\Throwable $th) {
            Helper::setNotify("Erro ao atualizar o plano. " . __("messages.contateSuporteTecnico"), 'danger|close-circle');
            return ["success" => false, "redirect" => redirect()->back()->withInput()];
        }
    }

    public function delete($id)
    {
        $this->planoRepository->delete($id);
    }
}
