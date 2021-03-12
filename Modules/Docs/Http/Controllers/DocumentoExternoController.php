<?php

namespace Modules\Docs\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Core\Repositories\{EmpresaRepository, SetorRepository};
use Modules\Docs\Repositories\DocumentoExternoRepository;
use App\Classes\Helper;
use Illuminate\Support\Facades\{Auth, DB, Validator};
use Modules\Docs\Services\DocumentoExternoService;

class DocumentoExternoController extends Controller
{
    protected $documentoExternoRepository;
    protected $setorRepository;
    protected $empresaRepository;

    public function __construct()
    {
        $this->documentoExternoRepository = new DocumentoExternoRepository();
        $this->setorRepository = new SetorRepository();
        $this->empresaRepository = new EmpresaRepository();
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $documentos = $this->documentoExternoRepository->findAll();
        return view('docs::documento-externo.index', compact('documentos'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $setores = $this->setorRepository->findBy(
            [
                ['nome', '!=', 'Sem Setor'],
                ['inativo', '=', 0]
            ],
            [],
            [
                ['nome', 'ASC']
            ]
        );
        $setores = array_column(json_decode(json_encode($setores), true), 'nome', 'id');

        $fornecedores = $this->empresaRepository->findAll();
        $fornecedores = array_column(json_decode(json_encode($fornecedores), true), 'nome', 'id');

        return view('docs::documento-externo.create', compact('setores', 'fornecedores'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $documentoExternoService = new DocumentoExternoService();
        $montaRequest = $this->montaRequest($request);
        $reponse = $documentoExternoService->store($montaRequest);

        if (!$reponse['success']) {
            return $reponse['redirect'];
        } else {
            Helper::setNotify('Novo documento externo criado com sucesso!', 'success|check-circle');
            return redirect()->route('docs.documento-externo');
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
        $documento = $this->documentoExternoRepository->find($id);
        $setores = $this->setorRepository->findBy(
            [
                ['nome', '!=', 'Sem Setor'],
                ['inativo', '=', 0]
            ],
            [],
            [
                ['nome', 'ASC']
            ]
        );
        $setores = array_column(json_decode(json_encode($setores), true), 'nome', 'id');

        $fornecedores = $this->empresaRepository->findAll();
        $fornecedores = array_column(json_decode(json_encode($fornecedores), true), 'nome', 'id');

        return view('docs::documento-externo.edit', compact('documento', 'setores', 'fornecedores'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request)
    {
        $documentoExternoService = new DocumentoExternoService();
        $montaRequest = $this->montaRequest($request);
        $reponse = $documentoExternoService->update($montaRequest);

        if (!$reponse['success']) {
            return $reponse['redirect'];
        } else {
            Helper::setNotify('Documento externo atualizado com sucesso!', 'success|check-circle');
            return redirect()->route('docs.documento-externo');
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
                $this->documentoExternoRepository->delete($id);
            });
            return response()->json(['response' => 'sucesso']);
        } catch (\Exception $th) {
            return response()->json(['response' => 'erro']);
        }
    }

    public function montaRequest(Request $request)
    {
        $retorno =  [
            "validado"                   => $request->get('lido') == 'on' ? true : false,
            "user_responsavel_upload_id" => Auth::user()->id,
            "user_id"                    => Auth::user()->id,
            "setor_id"                   => $request->get('setor'),
            "empresa_id"                 => $request->get('fornecedor'),
            "revisao"                    => $request->get('versao'),
            "validade"                   => $request->get('validade'),
            "ged_documento_id"           => '',
            "ged_registro_id"            => '',
            "ged_area_id"                => ''
        ];

        if ($request->idDocumento) {
            $retorno['id'] = $request->idDocumento;
        }

        return $retorno;
    }
}
