<?php

namespace Modules\Core\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Core\Repositories\NotificacaoRepository;
use Modules\Core\Repositories\ParametroRepository;
use Illuminate\Support\Facades\{DB};
use Modules\Core\Services\NotificacaoService;
use App\Classes\Helper;

class ModeloNotificacaoController extends Controller
{
    protected $notificacaoRepository;
    protected $parametroRepository;
    protected $notificacaoService;

    public function __construct(
        NotificacaoRepository $notificacaoRepository,
        ParametroRepository $parametroRepository,
        NotificacaoService $notificacaoService
    )
    {
        $this->notificacaoRepository = $notificacaoRepository;
        $this->parametroRepository = $parametroRepository;
        $this->notificacaoService = $notificacaoService;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $notificacoes = $this->notificacaoRepository->findAll();
        return view('core::modelo-notificacao.index', compact('notificacoes'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $buscaTiposEnvio = $this->parametroRepository->getParametro('TIPO_ENVIO_NOTIFICACAO');
        $tiposEnvio = json_decode($buscaTiposEnvio);
        return view('core::modelo-notificacao.create', compact('tiposEnvio'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $montaRequest = $this->montaRequest($request);
        $reponse = $this->notificacaoService->store($request, $montaRequest);

        if (is_object($reponse) && get_class($reponse) === "Illuminate\Http\RedirectResponse") {
            return $reponse;
        }

        if ($reponse) {
            Helper::setNotify('Nova notificação criada com sucesso!', 'success|check-circle');
            return redirect()->route('core.modelo-notificacao');
        }

        Helper::setNotify("Erro ao criar a notificação. " . __("messages.contateSuporteTecnico"), 'danger|close-circle');
        return redirect()->back();
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('core::show', );
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $modeloNotificacao = $this->notificacaoRepository->find($id);
        $buscaTiposEnvio = $this->parametroRepository->getParametro('TIPO_ENVIO_NOTIFICACAO');
        $tiposEnvio = json_decode($buscaTiposEnvio);

        return view('core::modelo-notificacao.edit', compact('tiposEnvio', 'modeloNotificacao'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request)
    {
        $montaRequest = $this->montaRequest($request);
        $reponse = $this->notificacaoService->update($request, $montaRequest);

        if (is_object($reponse) && get_class($reponse) === "Illuminate\Http\RedirectResponse") {
            return $reponse;
        }

        if ($reponse) {
            Helper::setNotify('Notificação atualizada com sucesso!', 'success|check-circle');
            return redirect()->route('core.modelo-notificacao');
        }

        Helper::setNotify("Erro ao atualizar a notificação. " . __("messages.contateSuporteTecnico"), 'danger|close-circle');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(Request $request)
    {
        $id = $request = $request->id;
        try {
            DB::transaction(function () use ($id) {
                $this->notificacaoService->delete($id);
            });
            return response()->json(['response' => 'sucesso']);
        } catch (\Exception $th) {
            return response()->json(['response' => 'erro']);
        }
    }

    public function montaRequest(Request $request)
    {
        return [
            "nome"    => $request->nome,
            "tipo_id" => 0,
            "titulo_email" => $request->titulo,
            "corpo_email" => $request->corpo,
            "tipo_envio_notificacao_id" => $request->tipoEnvio,
            "documento_anexo" => $request->enviarAnexo == '1' ? true : false
        ];
    }
}
