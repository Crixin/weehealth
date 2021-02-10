<?php

namespace Modules\Docs\Http\Controllers;

use App\Classes\Helper;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Docs\Repositories\BpmnRepository;
use Illuminate\Support\Facades\Validator;
use Modules\Docs\Services\BpmnService;

class BpmnController extends Controller
{
    protected $bpmnRepository;


    public function __construct()
    {
        $this->bpmnRepository = new BpmnRepository();
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $bpmns = $this->bpmnRepository->findAll();

        return view('docs::bpmn.index', compact('bpmns'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('docs::bpmn.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $cadastro = $this->montaRequest($request);
        $bpmnService = new BpmnService();
        $retorno = $bpmnService->create($cadastro);
        if (!$retorno['success']) {
            return $retorno['redirect'];
        } else {
            Helper::setNotify('Novo BPMN criado com sucesso!', 'success|check-circle');
            return redirect()->route('docs.bpmn');
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
        $bpmn = $this->bpmnRepository->find($id);
        return view('docs::bpmn.edit', compact('bpmn'));
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
        try {
            $buscaFluxo = $this->bpmnRepository->find($request->idBPMN);
            $update = $this->montaRequest($request);
            $update['versao'] = $buscaFluxo->versao + 1;
            $bpmnService = new BpmnService();
            $retorno = $bpmnService->update($update);
            if (!$retorno['success']) {
                return $retorno['redirect'];
            }
            Helper::setNotify('Informações do BPMN atualizadas com sucesso!', 'success|check-circle');
        } catch (\Throwable $th) {
            Helper::setNotify('Um erro ocorreu ao atualizar o BPMN', 'danger|close-circle');
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
            $bpmnService = new BpmnService();
            $retorno = $bpmnService->delete($id);
            if (!$retorno['success']) {
                throw new Exception("Um erro ocorreu ao excluir o bpmn", 1);
            }
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
                'nome'           => empty($request->get('idBPMN')) ? 'required|string|max:100|unique:docs_bpmn,nome' : 'required|string|min:5|max:100|unique:docs_bpmn,nome,' . $request->idBPMN,
                'versao'         => 'required|string'
            ]
        );

        if ($validator->fails()) {
            return $validator;
        } elseif (empty($request->arquivoXML2)) {
            Helper::setNotify('Desenhe algum BPMN', 'danger|close-circle');
            return $validator;
        }
        return false;
    }

    public function montaRequest(Request $request)
    {
        $retorno = [
            "nome"      => $request->get('nome'),
            "versao"    => $request->get('versao') ?? 1,
            "arquivo"   => $request->get('arquivoXML2')
        ];
        if ($request->idBPMN) {
            $retorno["id"] = $request->idBPMN;
        }

        return $retorno;
    }
}
