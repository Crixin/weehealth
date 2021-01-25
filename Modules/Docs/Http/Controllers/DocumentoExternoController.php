<?php

namespace Modules\Docs\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Core\Repositories\{EmpresaRepository, SetorRepository};
use Modules\Docs\Repositories\DocumentoExternoRepository;
use App\Classes\Helper;
use Illuminate\Support\Facades\{Auth, DB, Validator};

class DocumentoExternoController extends Controller
{
    protected $documentoExternoRepository;
    protected $setorRepository;
    protected $empresaRepository;

    public function __construct(
        DocumentoExternoRepository $documentoExternoRepository,
        SetorRepository $setorRepository,
        EmpresaRepository $empresaRepository
    )
    {
        $this->documentoExternoRepository = $documentoExternoRepository;
        $this->setorRepository = $setorRepository;
        $this->empresaRepository = $empresaRepository;
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
                ['nome', '!=', 'Sem Setor']
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
        $error = $this->validador($request);
        if ($error) {
            return redirect()->back()->withInput()->withErrors($error);
        }
        $cadastro = $this->montaRequest($request);

        try {
            DB::transaction(function () use ($cadastro) {
                $this->documentoExternoRepository->create($cadastro);
            });

            Helper::setNotify('Novo documento externo criado com sucesso!', 'success|check-circle');
            return redirect()->route('docs.documento-externo');
        } catch (\Throwable $th) {
            dd($th);
            Helper::setNotify('Um erro ocorreu ao gravar o documento externo', 'danger|close-circle');
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
        $documento = $this->documentoExternoRepository->find($id);
        $setores = $this->setorRepository->findBy(
            [
                ['nome', '!=', 'Sem Setor']
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
        $error = $this->validador($request);
        if ($error) {
            return redirect()->back()->withInput()->withErrors($error);
        }

        $fluxo = $request->get('idDocumento');
        $update  = $this->montaRequest($request);

        try {
            DB::transaction(function () use ($update, $fluxo) {
                $this->documentoExternoRepository->update($update, $fluxo);
            });

            Helper::setNotify('Informações do documento externo atualizadas com sucesso!', 'success|check-circle');
        } catch (\Throwable $th) {
            Helper::setNotify('Um erro ocorreu ao atualizar o documento externo', 'danger|close-circle');
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
                $this->documentoExternoRepository->delete($id);
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
                'setor'           => 'required|numeric',
                'fornecedor'      => 'required|numeric',
                'versao'          => 'required|numeric',
                'validade'        => 'required|date'
            ]
        );

        if ($validator->fails()) {
            return $validator;
        }

        return false;
    }

    public function montaRequest(Request $request)
    {
        return [
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
    }
}
