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


    public function create(Request $request, array $montaRequest)
    {
        try {
            $validacao = new ValidacaoService($this->rules, $request->all());

            $errors = $validacao->make();

            if ($errors) {
                return redirect()->back()->withErrors($errors)->withInput();
            }
            DB::transaction(function () use ($montaRequest) {
                $notificacao = $this->notificacaoRepository->create($montaRequest);
            });

            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }


    public function update(Request $request, array $montaRequest)
    {
        try {
            $this->rules['nome'] .= "," . $request->idModeloNotificacao;

            $validacao = new ValidacaoService($this->rules, $request->all());

            $errors = $validacao->make();

            if ($errors) {
                return redirect()->back()->withErrors($errors)->withInput();
            }

            DB::transaction(function () use ($request, $montaRequest) {
                $notificacao = $this->notificacaoRepository->update($montaRequest, $request->idModeloNotificacao);
            });

            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    public function delete($id)
    {
        $this->notificacaoRepository->delete($id);
    }
}
