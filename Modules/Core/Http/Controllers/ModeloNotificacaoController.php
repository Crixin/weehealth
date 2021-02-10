<?php

namespace Modules\Core\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Core\Repositories\{NotificacaoRepository, ParametroRepository};
use Illuminate\Support\Facades\{DB};
use Modules\Core\Services\NotificacaoService;
use App\Classes\Helper;

class ModeloNotificacaoController extends Controller
{
    protected $notificacaoRepository;
    protected $parametroRepository;


    public function __construct()
    {
        $this->notificacaoRepository = new NotificacaoRepository();
        $this->parametroRepository = new ParametroRepository();
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

        $buscaTiposNotificacao = $this->parametroRepository->getParametro('TIPO_NOTIFICACAO');
        $tiposNotificacao = json_decode($buscaTiposNotificacao);

        return view('core::modelo-notificacao.create', compact('tiposEnvio', 'tiposNotificacao'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {

        $notificacaoService = new NotificacaoService();
        $montaRequest = $this->montaRequest($request);
        $reponse = $notificacaoService->store($montaRequest);

        if (!$reponse['success']) {
            return $reponse['redirect'];
        } else {
            Helper::setNotify('Nova notificação criada com sucesso!', 'success|check-circle');
            return redirect()->route('core.modelo-notificacao');
        }
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

        $buscaTiposNotificacao = $this->parametroRepository->getParametro('TIPO_NOTIFICACAO');
        $tiposNotificacao = json_decode($buscaTiposNotificacao);

        return view('core::modelo-notificacao.edit', compact('tiposEnvio', 'modeloNotificacao', 'tiposNotificacao'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request)
    {
        $notificacaoService = new NotificacaoService();
        $montaRequest = $this->montaRequest($request);
        $reponse = $notificacaoService->update($montaRequest);

        if (!$reponse['success']) {
            return $reponse['redirect'];
        } else {
            Helper::setNotify('Notificação atualizada com sucesso!', 'success|check-circle');
            return redirect()->route('core.modelo-notificacao');
        }
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
                $notificacaoService = new NotificacaoService();
                $notificacaoService->delete($id);
            });
            return response()->json(['response' => 'sucesso']);
        } catch (\Exception $th) {
            return response()->json(['response' => 'erro']);
        }
    }

    public function montaRequest(Request $request)
    {
        $retorno =  [
            "nome"                    => $request->nome,
            "tipo_id"                 => 0,
            "titulo_email"            => $request->titulo,
            "corpo_email"             => $request->corpo,
            "tipo_envio_notificacao_id" => $request->tipoEnvio,
            "documento_anexo"         => $request->enviarAnexo == '1' ? true : false,
            'tipo_id'                 => $request->tipoNotificacao,
            'tempo_delay_envio'       => $request->delay,
            'numero_tentativas_envio' => $request->tentativas
        ];

        if ($request->idModeloNotificacao) {
            $retorno['id'] = $request->idModeloNotificacao;
        }
        return $retorno;
    }
}
