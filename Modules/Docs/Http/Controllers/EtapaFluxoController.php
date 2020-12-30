<?php

namespace Modules\Docs\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Docs\Repositories\EtapaFluxoRepository;
use Modules\Docs\Repositories\FluxoRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Classes\Helper;
use Modules\Core\Repositories\NotificacaoRepository;
use Modules\Core\Repositories\ParametroRepository;
use Modules\Core\Repositories\PerfilRepository;

class EtapaFluxoController extends Controller
{
    protected $etapaRepository;
    protected $fluxoRepository;
    protected $perfilRepository;
    protected $parametroRepository;
    protected $notificacaoRepository;

    public function __construct(
        EtapaFluxoRepository $etapaRepository,
        FluxoRepository $fluxoRepository,
        PerfilRepository $perfilRepository,
        ParametroRepository $parametroRepository,
        NotificacaoRepository $notificacaoRepository
    )
    {
        $this->etapaRepository = $etapaRepository;
        $this->fluxoRepository = $fluxoRepository;
        $this->perfilRepository = $perfilRepository;
        $this->parametroRepository = $parametroRepository;
        $this->notificacaoRepository = $notificacaoRepository;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index($id)
    {
        $fluxo  = $this->fluxoRepository->find($id);
        $etapas = $this->etapaRepository->findBy(
            [
                ['fluxo_id','=',$id]
            ]
        );

        return view('docs::etapa-fluxo.index', compact('etapas', 'fluxo'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create($id)
    {
        $fluxo  = $this->fluxoRepository->find($id);

        $buscaPerfil = $this->perfilRepository->findAll();
        $perfis = array_column(json_decode(json_encode($buscaPerfil), true), 'nome', 'id');

        $statusEtapa = $this->parametroRepository->getParametro('STATUS_ETAPA_FLUXO');
        $status = json_decode($statusEtapa);

        $buscaNotificacoes = $this->notificacaoRepository->findAll();
        $notificacoes = array_column(json_decode(json_encode($buscaNotificacoes), true), 'nome', 'id');

        $statusTipoAprovacao = $this->parametroRepository->getParametro('TIPO_APROVACAO_ETAPA');
        $tiposAprovacao = json_decode($statusTipoAprovacao);

        $etapasRejeicao  = $this->etapaRepository->findBy(
            [
                ['fluxo_id','=',$id]
            ]
        );
        $etapasRejeicao = array_column(json_decode(json_encode($etapasRejeicao), true), 'nome', 'id');

        return view('docs::etapa-fluxo.create',
            compact(
                'fluxo',
                'perfis',
                'status',
                'notificacoes',
                'tiposAprovacao',
                'etapasRejeicao'
            )
        );
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request, $id)
    {
        $error = $this->validador($request);
        if ($error) {
            return redirect()->back()->withInput()->withErrors($error);
        }

        $cadastro = $this->montaRequest($request, $id);
        try {
            DB::transaction(function () use ($cadastro) {
                $this->etapaRepository->create($cadastro);
            });

            Helper::setNotify('Nova etapa criada com sucesso!', 'success|check-circle');
            return redirect()->route('docs.fluxo.etapa-fluxo', ['fluxo_id' => $id]);
        } catch (\Throwable $th) {
            Helper::setNotify('Um erro ocorreu ao gravar a etapa do fluxo', 'danger|close-circle');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('docs::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id_fluxo, $id)
    {
        $etapaEdit = $this->etapaRepository->find($id);

        $buscaPerfil = $this->perfilRepository->findAll();
        $perfis = array_column(json_decode(json_encode($buscaPerfil), true), 'nome', 'id');

        $statusEtapa = $this->parametroRepository->getParametro('STATUS_ETAPA_FLUXO');
        $status = json_decode($statusEtapa);

        $buscaNotificacoes = $this->notificacaoRepository->findAll();
        $notificacoes = array_column(json_decode(json_encode($buscaNotificacoes), true), 'nome', 'id');

        $statusTipoAprovacao = $this->parametroRepository->getParametro('TIPO_APROVACAO_ETAPA');
        $tiposAprovacao = json_decode($statusTipoAprovacao);

        $etapasRejeicao  = $this->etapaRepository->findBy(
            [
                ['fluxo_id','=',$id_fluxo]
            ]
        );
        $etapasRejeicao = array_column(json_decode(json_encode($etapasRejeicao), true), 'nome', 'id');

        return view('docs::etapa-fluxo.edit', compact('etapaEdit', 'perfis', 'status', 'notificacoes', 'tiposAprovacao', 'etapasRejeicao'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request)
    {
        $error = $this->validador($request);
        if ($error) {
            return redirect()->back()->withInput()->withErrors($error);
        }

        $idEtapa = $request->get('id');
        $idFluxo = $request->get('fluxo_id');

        $update  = $this->montaRequest($request, $idFluxo);
        try {
            DB::transaction(function () use ($update, $idEtapa) {
                $this->etapaRepository->update($update, $idEtapa);
            });

            Helper::setNotify('Informações da etapa atualizadas com sucesso!', 'success|check-circle');
        } catch (\Throwable $th) {
            dd($th);
            Helper::setNotify('Um erro ocorreu ao atualizar a etapa', 'danger|close-circle');
        }
        return redirect()->back()->withInput();
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
                $this->etapaRepository->delete($id);
            });
            return response()->json(['response' => 'sucesso']);
        } catch (\Exception $th) {
            return response()->json(['response' => 'erro']);
        }
    }


    public function validador(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'nome'               => 'required|string|min:5|max:100',
                'descricao'          => 'required|string|min:5|max:200',
                'status'             => 'required|numeric',
                'perfil'             => 'required|numeric',
                'tipoAprovacao'      => 'required|numeric',
            ]
        );

        if ($validator->fails()) {
            return $validator;
        }

        return false;
    }

    public function montaRequest(Request $request, $id_fluxo)
    {
        $buscaUltimaEtapa = $this->etapaRepository->findBy(
            [
                ['fluxo_id','=',$id_fluxo]
            ],
            []
        );
 
        return [
            "nome"                       => $request->get('nome'),
            "descricao"                  => $request->get('descricao'),
            "perfil_id"                  => $request->get('perfil'),
            "fluxo_id"                   => $id_fluxo,
            "versao_fluxo"               => '',
            "status_id"                  => $request->get('status'),
            "ordem"                      => $request->get('ordem') ? $request->get('ordem') : $buscaUltimaEtapa->count() + 1,
            "enviar_notificacao"         => $request->get('enviarNotificacao') == 1 ? true : false,
            "notificacao_id"             => $request->get('notificacao'),
            "permitir_anexo"             => $request->get('permitirAnexo') == 1 ? true : false,
            "comportamento_criacao"      => $request->get('comportamentoCriacao') == 1 ? true : false,
            "comportamento_edicao"       => $request->get('comportamentoEdicao') == 1 ? true : false,
            "comportamento_aprovacao"    => $request->get('comportamentoAprovacao') == 1 ? true : false,
            "comportamento_visualizacao" => $request->get('comportamentoVizualizacao') == 1 ? true : false,
            "comportamento_divulgacao"   => $request->get('comportamentoDivulgacao') == 1 ? true : false,
            "comportamento_treinamento"  => $request->get('comportamentoTreinamento') == 1 ? true : false,
            "tipo_aprovacao_id"          => $request->get('tipoAprovacao'),
            "obrigatorio"                => $request->get('obrigatoria') == 1 ? true : false,
            "etapa_rejeicao_id"          => $request->get('etapaRejeicao'),
            "exigir_lista_presenca"      => $request->get('listaPresenca') == 1 ? true : false,
        ];
    }
}
