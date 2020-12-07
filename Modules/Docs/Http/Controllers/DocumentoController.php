<?php

namespace Modules\Docs\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Docs\Repositories\DocumentoRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Classes\Helper;

class DocumentoController extends Controller
{
    protected $documentoRepository;

    public function __construct(DocumentoRepository $documentoRepository)
    {
        $this->documentoRepository = $documentoRepository;
    }

   
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $documentos = $this->documentoRepository->findAll();
        return view('docs::documento.index', compact('documentos'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('docs::documento.create');
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
                $this->documentoRepository->create($cadastro);
            });

            Helper::setNotify('Novo documento criado com sucesso!', 'success|check-circle');
            return redirect()->route('docs.documento');
        } catch (\Throwable $th) {
            Helper::setNotify('Um erro ocorreu ao gravar o documento', 'danger|close-circle');
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
        return view('docs::documento.edit');
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

        $id = $request->get('idDocumento');

        $update  = $this->montaRequest($request, $id);
        try {
            DB::transaction(function () use ($update, $id) {
                $this->documentoRepository->update($update, $id);
            });

            Helper::setNotify('Informações do documento atualizadas com sucesso!', 'success|check-circle');
        } catch (\Throwable $th) {
            dd($th);
            Helper::setNotify('Um erro ocorreu ao atualizar o documento', 'danger|close-circle');
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
                $this->documentoRepository->delete($id);
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
                'codigo'        => 'required|string|min:5|max:100',
                'nome'          => 'required|string|min:5|max:200',
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
            "codigo"                => $request->get('codigo'),
            "nome"                  => $request->get('nome')
        ];
    }
}
