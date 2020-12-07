<?php

namespace Modules\Docs\Http\Controllers;

use App\Classes\Helper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Core\Repositories\ParametroRepository;
use Modules\Docs\Repositories\NormaRepository;

class NormaController extends Controller
{
    protected $normaRepository;
    protected $parametroRepository;

    

    public function __construct(NormaRepository $normaRepository, ParametroRepository $parametroRepository)
    {
        $this->middleware('auth');
        $this->normaRepository = $normaRepository;
        $this->parametroRepository = $parametroRepository;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $normas = $this->normaRepository->findBy(
            [],
            [],
            [
                ['nome','ASC']
            ]
        );
        
        return view('docs::norma.index', compact('normas'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $buscaOrgaos = $this->parametroRepository->getParametro('ORGAO_REGULADOR');
        $orgaos = json_decode($buscaOrgaos);


        $buscaCicloAuditoria = $this->parametroRepository->getParametro('CICLO_AUDITORIA');
        $ciclos = json_decode($buscaCicloAuditoria);

        return view('docs::norma.create', compact('orgaos', 'ciclos'));
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
                $this->normaRepository->create($cadastro);
            });

            Helper::setNotify('Nova norma criada com sucesso!', 'success|check-circle');
            return redirect()->route('docs.norma');
        } catch (\Throwable $th) {
            Helper::setNotify('Um erro ocorreu ao gravar a norma', 'danger|close-circle');
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
        $norma = $this->normaRepository->find($id);

        $buscaOrgaos = $this->parametroRepository->getParametro('ORGAO_REGULADOR');
        $orgaos = json_decode($buscaOrgaos);


        $buscaCicloAuditoria = $this->parametroRepository->getParametro('CICLO_AUDITORIA');
        $ciclos = json_decode($buscaCicloAuditoria->valor_padrao);

        return view('docs::norma.edit', compact('norma', 'orgaos', 'ciclos'));
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

        $norma = $request->get('idNorma');
        $update  = $this->montaRequest($request);
        try {
            DB::transaction(function () use ($update, $norma) {
                $this->normaRepository->update($update, $norma);
            });

            Helper::setNotify('Informações da norma atualizadas com sucesso!', 'success|check-circle');
        } catch (\Throwable $th) {
            Helper::setNotify('Um erro ocorreu ao atualizar a norma', 'danger|close-circle');
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
                $this->normaRepository->delete($id);
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
                'descricao'          => empty($request->get('idNorma')) ? 'required|string|min:5|max:100|unique:docs_norma' : '',
                'orgaoRegulador'     => 'required|numeric',
                'cicloAuditoria'     => 'required|numeric',
                'dataAcreditacao'    => 'required|date'
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
            "descricao"             => $request->get('descricao'),
            "orgao_regulador_id"    => $request->get('orgaoRegulador'),
            "ativo"                 => $request->get('vigente') == 1 ? true : false,
            "ciclo_auditoria_id"    => $request->get('cicloAuditoria'),
            "data_acreditacao"      => $request->get('dataAcreditacao'),
        ];
    }
}
