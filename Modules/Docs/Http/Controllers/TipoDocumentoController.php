<?php

namespace Modules\Docs\Http\Controllers;

use App\Classes\Helper;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\{DB, Validator};
use Modules\Core\Repositories\ParametroRepository;
use Modules\Core\Services\NotificacaoService;
use Modules\Docs\Repositories\{FluxoRepository, TipoDocumentoRepository};
use Modules\Docs\Services\TipoDocumentoService;

class TipoDocumentoController extends Controller
{
    protected $tipoDocumentoRepository;
    protected $fluxoRepository;
    protected $parametroRepository;
    protected $tipoDocumentoService;

    public function __construct()
    {
        $this->tipoDocumentoRepository = new TipoDocumentoRepository();
        $this->fluxoRepository = new FluxoRepository();
        $this->parametroRepository = new ParametroRepository();
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $tipos = $this->tipoDocumentoRepository->findBy(
            [],
            [],
            [
                ['name', 'ASC']
            ]
        );
        return view('docs::tipo-documento.index', compact('tipos'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
 
        $fluxos = $this->fluxoRepository->findAll();

        $fluxos = $fluxos->count() > 0 ? array_column(json_decode(json_encode($fluxos), true), 'nome', 'id') : [];


        $tiposDocumento = $this->tipoDocumentoRepository->findBy(
            [
                ['ativo', '=', true],
            ]
        );
        $tiposDocumento = $tiposDocumento ? array_column(json_decode(json_encode($tiposDocumento), true), 'nome', 'id') : [];

        $padroesCodigo = $this->parametroRepository->getParametro('PADRAO_CODIGO');
        $padroesCodigo = $padroesCodigo ? array_column((array)json_decode($padroesCodigo), 'DESCRICAO', 'ID') : [];

        $padroesNumero = $this->parametroRepository->getParametro('PADRAO_NUMERO');
        $padroesNumero = $padroesNumero ? array_column((array)json_decode($padroesNumero), 'DESCRICAO', 'ID') : [];

        $extensoesDocumentos = $this->parametroRepository->getParametro('EXTENSAO_DOCUMENTO_ONLYOFFICE');

        return view(
            'docs::tipo-documento.create',
            compact(
                'fluxos',
                'tiposDocumento',
                'padroesCodigo',
                'padroesNumero',
                'extensoesDocumentos'
            )
        );
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $tipoDocumento = new TipoDocumentoService();
        $montaRequest = $this->montaRequest($request);
        $reponse = $tipoDocumento->store($montaRequest);

        if (!$reponse['success']) {
            return $reponse['redirect'];
        } else {
            Helper::setNotify('Novo tipo de documento criado com sucesso!', 'success|check-circle');
            return redirect()->route('docs.tipo-documento');
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
    public function edit($id)
    {
        $tipoDocumento = $this->tipoDocumentoRepository->find($id);
        $fluxos = $this->fluxoRepository->findAll();

        $fluxos = $fluxos->count() > 0 ? array_column(json_decode(json_encode($fluxos), true), 'nome', 'id') : [];

        $tiposDocumento = $this->tipoDocumentoRepository->findBy(
            [
                ['ativo', '=', true],
                ['id', '!=', $id]
            ]
        );
        $tiposDocumento = $tiposDocumento ? array_column(json_decode(json_encode($tiposDocumento), true), 'nome', 'id') : [];


        $padroesCodigoParametro = (array)json_decode($this->parametroRepository->getParametro('PADRAO_CODIGO'));
        $padroesCodigoParametro = $padroesCodigoParametro ? array_column($padroesCodigoParametro, 'DESCRICAO', 'ID') : [];

        $padroesCodigo = array();
        $resto = array();
        foreach ($padroesCodigoParametro as $key => $value) {
            if (!in_array($key, json_decode ( $tipoDocumento->codigo_padrao ) )) {
                $resto += [
                    $key => $value
                ];
            }
        }
        foreach (json_decode($tipoDocumento->codigo_padrao) as $key => $value) {
            $padroesCodigo += [
                $value => $padroesCodigoParametro[$value]
            ];
        }
        $padroesCodigo += $resto;

        $padroesNumero = $this->parametroRepository->getParametro('PADRAO_NUMERO');
        $padroesNumero = $padroesNumero ? array_column((array)json_decode($padroesNumero), 'DESCRICAO', 'ID') : [];


        $extensoesDocumentos = $this->parametroRepository->getParametro('EXTENSAO_DOCUMENTO_ONLYOFFICE');

        return view(
            'docs::tipo-documento.edit',
            compact(
                'tipoDocumento',
                'fluxos',
                'tiposDocumento',
                'padroesCodigo',
                'padroesNumero',
                'extensoesDocumentos'
            )
        );
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request)
    {
        $tipoDocumento = new TipoDocumentoService();
        $montaRequest = $this->montaRequest($request);
        $reponse = $tipoDocumento->update($montaRequest);

        if (!$reponse['success']) {
            return $reponse['redirect'];
        } else {
            Helper::setNotify('Informações do tipo de documento atualizadas com sucesso!', 'success|check-circle');
            return redirect()->route('docs.tipo-documento');
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
                $this->tipoDocumentoRepository->delete($id);
            });
            return response()->json(['response' => 'sucesso']);
        } catch (\Exception $th) {
            return response()->json(['response' => 'erro']);
        }
    }


    public function getEtapaFluxo(Request $request)
    {
        try {
            $id = $request->id;
            $tipoDocumentoService = new TipoDocumentoService();
            $etapas = $tipoDocumentoService->getEtapasFluxosPorComportamento($id, 'comportamento_aprovacao');
            ksort($etapas);
            return response()->json(['response' => 'sucesso', 'data' => $etapas]);
        } catch (\Exception $th) {
            return response()->json(['response' => 'erro']);
        }
    }

    public function montaRequest(Request $request)
    {

        if ($request->documentoModelo) {
            $mimeType = $request->file('documentoModelo')->getMimeType();
            $extensao = $request->file('documentoModelo')->getClientOriginalExtension();
            $imageBase64 = base64_encode(file_get_contents($request->file('documentoModelo')->getRealPath()));
            $imageBase64 = 'data:' . $mimeType . ';base64,' . $imageBase64;
        }

        $retorno = [
            "nome"                  => $request->get('nome'),
            "descricao"             => $request->get('descricao'),
            "sigla"                 => $request->get('sigla'),
            "fluxo_id"              => $request->get('fluxo'),
            "tipo_documento_pai_id" => $request->get('tipoDocumentoPai') ?? null,
            "periodo_vigencia"      => $request->get('periodoVigencia'),
            "ativo"                 => $request->get('ativo') == 1 ? true : false,
            "vinculo_obrigatorio"   => $request->get('vinculoObrigatorio') == 1 ? true : false,
            'vinculo_obrigatorio_outros_documento' => $request->get('vinculoObrigatorioOutrosDocs') == 1 ? true : false,
            "permitir_download"     => $request->get('permitirDownload') == 1 ? true : false,
            "permitir_impressao"    => $request->get('permitirImpressao') == 1 ? true : false,
            "periodo_aviso"         => $request->get('periodoAviso'),
            "modelo_documento"      => $imageBase64 ?? '',
            "mime_type"             => $request->file('documentoModelo'),
            "extensao"              => $extensao ?? '',
            "codigo_padrao"         => json_encode($request->get('codigoPadrao')),
            "numero_padrao_id"      => $request->get('numeroPadrao'),
            "ultimo_documento"      => $request->get('ultimoDocumento') ?? 0
        ];

        //REMOVENDO OS CAMPOS DO DOCUMENTO MODELO NO MOMENTO DO UPDATE PARA CASO O USUÁRIO NAO
        //TENHA SUBIDO UM NOVO ARQUIVO PARA QUE ASSIM NÃO SUBSTITUA O MODELO ATUAL POR VAZIO
        if (!$request->documentoModelo) {
            unset($retorno["modelo_documento"], $retorno["extensao"], $retorno['mime_type']);
        }

        //SE TIVER DOCUMENTO VINCULADO A ESSE FLUXO NÃO ALTERA O ULTIMO DOCUMENTO
        if ($request->get('idTipoDocumento')) {
/*             $buscaDocumento = $this->tipoDocumentoRepository->find($request->get('idTipoDocumento'));
            if ($buscaDocumento->docsDocumento->count() > 0) {
                unset($retorno["ultimo_documento"]);
            }
 */
            $retorno['id'] = $request->get('idTipoDocumento');
        }

        return $retorno;
    }
}
