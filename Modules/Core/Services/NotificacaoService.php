<?php

namespace Modules\Core\Services;

use App\Classes\Helper;
use App\Services\ValidacaoService;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Modules\Core\Model\Notificacao;
use Modules\Core\Repositories\NotificacaoRepository;

class NotificacaoService
{

    private $rules;
    private $notificacaoRepository;

    public function __construct()
    {
        $notificacao = new Notificacao();
        $this->rules = $notificacao->rules;
        $this->notificacaoRepository = new NotificacaoRepository();
    }


    public function store(array $montaRequest)
    {
        try {
            $insert = [
                "nome"              => $montaRequest['nome'],
                "tipoNotificacao"   => $montaRequest['tipo_id'],
                "titulo"            => $montaRequest['titulo_email'],
                "corpo"             => $montaRequest['corpo_email'],
                "tipoEnvio"         => $montaRequest['tipo_envio_notificacao_id'],
                "delay"             => $montaRequest['tempo_delay_envio'],
                "tentativas"        => $montaRequest['numero_tentativas_envio']
            ];

            $validacao = new ValidacaoService($this->rules, $insert);
            $errors = $validacao->make();
            if ($errors) {
                return ["success" => false, "redirect" => redirect()->back()->withErrors($errors)->withInput()];
            }

            DB::transaction(function () use ($montaRequest) {
                $notificacao = $this->notificacaoRepository->create($montaRequest);
            });
            return ["success" => true];
        } catch (\Throwable $th) {
            Helper::setNotify("Erro ao cadastrar a notificação. " . __("messages.contateSuporteTecnico"), 'danger|close-circle');
            return ["success" => false, "redirect" => redirect()->back()->withInput()];
        }
    }


    public function update(array $montaRequest)
    {
        try {
            $this->rules['nome'] .= "," . $montaRequest['id'];

            $insert = [
                "nome"              => $montaRequest['nome'],
                "tipoNotificacao"   => $montaRequest['tipo_id'],
                "titulo"            => $montaRequest['titulo_email'],
                "corpo"             => $montaRequest['corpo_email'],
                "tipoEnvio"         => $montaRequest['tipo_envio_notificacao_id'],
                "delay"             => $montaRequest['tempo_delay_envio'],
                "tentativas"        => $montaRequest['numero_tentativas_envio']
            ];

            $validacao = new ValidacaoService($this->rules, $insert);

            $errors = $validacao->make();

            if ($errors) {
                return ["success" => false, "redirect" => redirect()->back()->withErrors($errors)->withInput()];
            }
            DB::transaction(function () use ($montaRequest) {
                $notificacao = $this->notificacaoRepository->update($montaRequest, $montaRequest['id']);
            });

            return ["success" => true];
        } catch (\Throwable $th) {
            Helper::setNotify("Erro ao atualizar a notificação. " . __("messages.contateSuporteTecnico"), 'danger|close-circle');
            return ["success" => false, "redirect" => redirect()->back()->withInput()];
        }
    }

    public function delete($id)
    {
        $this->notificacaoRepository->delete($id);
    }
}
