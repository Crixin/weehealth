<?php

namespace Modules\Core\Services;

use App\Classes\Helper;
use App\Mail\PadraoDocs;
use App\Notifications\GeneralNotification;
use App\Services\ValidacaoService;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Modules\Core\Http\Controllers\JobController;
use Modules\Core\Model\Notificacao;
use Modules\Core\Repositories\NotificacaoRepository;
use Modules\Core\Repositories\UserRepository;

class NotificacaoService
{

    private $rules;
    private $notificacaoRepository;
    private $userRepository;

    public function __construct()
    {
        $notificacao = new Notificacao();
        $this->rules = $notificacao->rules;
        $this->notificacaoRepository = new NotificacaoRepository();
        $this->userRepository = new UserRepository();
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

    public function sendNotification(int $idNotificacao, array $usuarios, $obsEmailCorpo = '', string $titulo = '', string $corpo = '')
    {
        $buscaNotificacao = $this->notificacaoRepository->find($idNotificacao);
        $jobController = new JobController();
        /*PODE HAVER OUTRAS FORMAS DE ENVIO DE NOTIFICACAO EX: WHATSAPP */
        switch ($buscaNotificacao->tipo_envio_notificacao_id) {
            case '1':
                //envio por email
                $responde = $jobController->enqueueEmail($usuarios, $obsEmailCorpo, $buscaNotificacao->numero_tentativas_envio, $buscaNotificacao->tempo_delay_envio);
                break;
        }

        return $responde;
    }

    public function createNotificacaoSistema(array $users, string $titulo, string $corpo, string $link)
    {
        foreach ($users as $key => $user) {
            $buscaUser = $this->userRepository->findOneBy(
                [
                    ['email', '=', $user]
                ]
            );
            Notification::send($buscaUser, new GeneralNotification($titulo, $corpo, $link));
        }
        return true;
    }

    public function getCorpoNotificacao($idDocumento, $etapa, $notificacaoPreferencial = '')
    {
        $idNotificacao = $notificacaoPreferencial != '' ? $notificacaoPreferencial : $etapa->notificacao_id;
        $buscaNotificacao = $this->notificacaoRepository->find($idNotificacao);
        $corpo = '';
        switch ($buscaNotificacao->tipo_id) {
            //Pode haver varios tipos de corpo de email (hoje soh tem um para teste)
            case '2':
                //Documento publicado
                $corpo = new PadraoDocs($etapa, $idDocumento, $idNotificacao);
                break;
            case '3':
                //Documento com copia controlada
                $corpo = new PadraoDocs($etapa, $idDocumento, $idNotificacao);
                break;
            case '5':
                //Documento que precisa de Verificacao
                $corpo = new PadraoDocs($etapa, $idDocumento, $idNotificacao);
                break;
            case '6':
                //Rejeição do documento
                $corpo = new PadraoDocs($etapa, $idDocumento, $idNotificacao);
                break;
            case '7':
                //Aprovação do documento
                $corpo = new PadraoDocs($etapa, $idDocumento, $idNotificacao);
                break;
        }
        return $corpo;
    }
}
