<?php

namespace Modules\Docs\Http\Controllers;

use App\Classes\Helper;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\Core\Repositories\ParametroRepository;
use Modules\Docs\Repositories\FluxoRepository;
use Modules\Docs\Repositories\TipoDocumentoRepository;

class TipoDocumentoController extends Controller
{
    protected $tipoDocumentoRepository;
    protected $fluxoRepository;
    protected $parametroRepository;

    public function __construct(
        TipoDocumentoRepository $tipoDocumentoRepository,
        FluxoRepository $fluxoRepository,
        ParametroRepository $parametroRepository
    ){
        $this->tipoDocumentoRepository = $tipoDocumentoRepository;
        $this->fluxoRepository = $fluxoRepository;
        $this->parametroRepository = $parametroRepository;
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

        $periodosVigencia = $this->parametroRepository->findOneBy(
            [
                ['identificador_parametro','=','PERIODO_VIGENCIA']
            ]
        );

        $periodosVigencia = array_column(json_decode($periodosVigencia->valor_padrao), 'descricao', 'id');

        $periodosAviso = $this->parametroRepository->findOneBy(
            [
                ['identificador_parametro','=','PERIODO_AVISO_VENCIMENTO']
            ]
        );
        $periodosAviso = array_column(json_decode($periodosAviso->valor_padrao), 'descricao', 'id');

        $tiposDocumento = $this->tipoDocumentoRepository->findBy(
            [
                ['ativo', '=', true],
            ]
        );
        $tiposDocumento = $tiposDocumento->count() > 0 ? array_column(json_decode(json_encode($tiposDocumento), true), 'nome', 'id') : [];

        $padroesCodigo = $this->parametroRepository->findOneBy(
            [
                ['identificador_parametro','=','PADRAO_CODIGO']
            ]
        );

        $padroesCodigo = $padroesCodigo->count() > 0 ? array_column(json_decode($padroesCodigo->valor_padrao), 'descricao', 'id') : [];

        return view('docs::tipo-documento.create', compact('fluxos', 'periodosVigencia', 'periodosAviso', 'tiposDocumento', 'padroesCodigo')
        );
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $error = $this->validador($request);
        if ($error) {
            return redirect()->back()->withInput()->withErrors($error);
        }

        $cadastro = self::montaRequest($request);
        try {
            DB::transaction(function () use ($cadastro, $request) {
                $this->tipoDocumentoRepository->create($cadastro);
            });

            Helper::setNotify('Novo tipo de documento criado com sucesso!', 'success|check-circle');
            return redirect()->route('docs.tipo-documento');
        } catch (\Throwable $th) {
            Helper::setNotify('Um erro ocorreu ao gravar o tipo de documento', 'danger|close-circle');
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
    public function edit($id)
    { 
        $tipoDocumento = $this->tipoDocumentoRepository->find($id);
        $fluxos = $this->fluxoRepository->findAll();

        $fluxos = $fluxos->count() > 0 ? array_column(json_decode(json_encode($fluxos), true), 'nome', 'id') : [];

        $periodosVigencia = $this->parametroRepository->findOneBy(
            [
                ['identificador_parametro','=','PERIODO_VIGENCIA']
            ]
        );

        $periodosVigencia = array_column(json_decode($periodosVigencia->valor_padrao), 'descricao', 'id');

        $periodosAviso = $this->parametroRepository->findOneBy(
            [
                ['identificador_parametro','=','PERIODO_AVISO_VENCIMENTO']
            ]
        );
        $periodosAviso = array_column(json_decode($periodosAviso->valor_padrao), 'descricao', 'id');

        $tiposDocumento = $this->tipoDocumentoRepository->findBy(
            [
                ['ativo', '=', true],
            ]
        );
        $tiposDocumento = $tiposDocumento->count() > 0 ? array_column(json_decode(json_encode($tiposDocumento), true), 'nome', 'id') : [];

        $padroesCodigo = $this->parametroRepository->findOneBy(
            [
                ['identificador_parametro','=','PADRAO_CODIGO']
            ]
        );

        $padroesCodigo = $padroesCodigo->count() > 0 ? array_column(json_decode($padroesCodigo->valor_padrao), 'descricao', 'id') : [];

        return view('docs::tipo-documento.edit',
            compact('tipoDocumento', 'fluxos', 'periodosVigencia', 'periodosAviso', 'tiposDocumento', 'padroesCodigo')
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
        $error = $this->validador($request);
        if ($error) {
            return redirect()->back()->withInput()->withErrors($error);
        }

        $tipoDocumento = $request->get('idTipoDocumento');
        $update  = self::montaRequest($request);
        try {
            DB::transaction(function () use ($update, $tipoDocumento) {
                $this->tipoDocumentoRepository->update($update, $tipoDocumento);
            });

            Helper::setNotify('Informações do tipo de documento atualizadas com sucesso!', 'success|check-circle');
        } catch (\Throwable $th) {
            Helper::setNotify('Um erro ocorreu ao atualizar o tipo de documento', 'danger|close-circle');
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
                $this->tipoDocumentoRepository->delete($id);
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
                'nome'                  => empty($request->get('idTipoDocumento')) ? 'required|string|max:100|unique:docs_tipo_documento' : '',
                'descricao'             => 'required|string|min:5|max:200',
                'sigla'                 => 'required|string|min:1|max:5',
                'fluxo'                 => 'required|string|max:50',
                'periodoVigencia'       => 'required|numeric',
                'ativo'                 => 'required|',
                'vinculoObrigatorio'    => 'required|',
                'permitirDownload'      => 'required|',
                'permitirImpressao'     => 'required|',
                'periodoAviso'          => 'required|numeric',
                'documentoModelo'       => empty($request->get('idTipoDocumento')) ? 'required|mimes:xls,doc' : '',
                'codigoPadrao'          => 'required',
            ]
        );


        if ($validator->fails()) {
            return $validator;
        }
        return false;
    }

    public function montaRequest(Request $request)
    {
        if ($request->get('documentoModelo')) {
            $mimeType = $request->file('documentoModelo')->getMimeType();
            $imageBase64 = base64_encode(file_get_contents($request->file('documentoModelo')->getRealPath()));
            $imageBase64 = 'data:' . $mimeType . ';base64,' . $imageBase64;
        } else {
            $buscaTipoDocumento = $this->tipoDocumentoRepository->find($request->get('idTipoDocumento'));
            $imageBase64 = $buscaTipoDocumento->documento_modelo ?? null;
        }

        return [
            "nome"                  => $request->get('nome'),
            "descricao"             => $request->get('descricao'),
            "sigla"                 => $request->get('sigla'),
            "fluxo_id"              => $request->get('fluxo'),
            "tipo_documento_pai_id" => $request->get('tipoDocumentoPai') ?? null,
            "periodo_vigencia_id"   => $request->get('periodoVigencia'),
            "ativo"                 => $request->get('ativo') == 1 ? true : false,
            "vinculo_obrigatorio"   => $request->get('vinculoObrigatorio') == 1 ? true : false,
            "permitir_download"     => $request->get('permitirDownload') == 1 ? true : false,
            "permitir_impressao"    => $request->get('permitirImpressao') == 1 ? true : false,
            "periodo_aviso_id"      => $request->get('periodoAviso'),
            "documento_modelo"      => $imageBase64,
            "codigo_padrao"         => $request->get('codigoPadrao')
        ];
    }
}
