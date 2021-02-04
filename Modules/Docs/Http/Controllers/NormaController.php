<?php

namespace Modules\Docs\Http\Controllers;

use App\Classes\Helper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Core\Repositories\ParametroRepository;
use Modules\Docs\Repositories\{CheckListItemNormaRepository, ItemNormaRepository, NormaRepository};
use Modules\Docs\Services\{CheckListItemNormaService, ItemNormaService};

class NormaController extends Controller
{
    protected $normaRepository;
    protected $parametroRepository;
    protected $checkListItemNormaRepository;
    protected $itemNormaRepository;
    protected $itemNormaService;

    public function __construct()
    {
        $this->middleware('auth');
        $this->normaRepository = new NormaRepository();
        $this->parametroRepository = new ParametroRepository();
        $this->checkListItemNormaRepository = new CheckListItemNormaRepository();
        $this->itemNormaRepository = new ItemNormaRepository();
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

        $itens = [];

        return view('docs::norma.create', compact('orgaos', 'ciclos', 'itens'));
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
            DB::transaction(function () use ($cadastro, $request) {
                $norma = $this->normaRepository->create($cadastro);
                if (!empty($request->arrayDataTable[0])) {
                    foreach (json_decode($request->arrayDataTable[0]) as $key => $value) {
                        $value = (array) $value;
                        $requestItemNorma = [
                            "norma_id" => $norma->id,
                            "numero"   => $value[0],
                            "descricao" => $value[1]
                        ];
                        $itemNormaService = new ItemNormaService();
                        $itemNorma = $itemNormaService->create($requestItemNorma);
 
                        if ($value[2] != '') {
                            $requestCheckList = [
                                "item_norma_id" => $itemNorma->id,
                                "descricao" => $value[2]
                            ];
                            $checkListItemNormaService = new CheckListItemNormaService();
                            $checkListItemNormaService->create($requestCheckList);
                        }
                    }
                }
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

        $itens = [];

        foreach ($norma->docsItemNorma as $key => $value) {
            $checkList = $this->checkListItemNormaRepository->findOneBy(
                [
                    ['item_norma_id', '=', $value->id]
                ]
            );
            $aux = [
                $value->numero,
                $value->descricao,
                $checkList->descricao ?? ''
            ];
            array_push($itens, $aux);
        }
        $buscaOrgaos = $this->parametroRepository->getParametro('ORGAO_REGULADOR');
        $orgaos = json_decode($buscaOrgaos);

        $buscaCicloAuditoria = $this->parametroRepository->getParametro('CICLO_AUDITORIA');
        $ciclos = json_decode($buscaCicloAuditoria);
        return view('docs::norma.edit', compact('norma', 'orgaos', 'ciclos', 'itens'));
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
            DB::transaction(function () use ($update, $norma, $request) {
                $this->normaRepository->update($update, $norma);

                //delete itens da norma e Checklist
                $buscaItensNorma = $this->itemNormaRepository->findBy(
                    [
                        ['norma_id', '=', $norma]
                    ]
                );
                foreach ($buscaItensNorma as $key => $valueItem) {
                    $buscaChecklist = $this->checkListItemNormaRepository->findBy(
                        [
                            ['item_norma_id', '=', $valueItem->id]
                        ]
                    );
                    foreach ($buscaChecklist as $key => $valueCheckList) {
                        $this->checkListItemNormaRepository->delete($valueCheckList->id);
                    }
                    $this->itemNormaRepository->delete($valueItem->id);
                }


                //cria
                if (!empty($request->arrayDataTable[0])) {
                    foreach (json_decode($request->arrayDataTable[0]) as $key => $value) {
                        $value = (array) $value;
                        $requestItemNorma = [
                            "norma_id" => $norma,
                            "numero"   => $value[0],
                            "descricao" => $value[1]
                        ];
                        $itemNormaService = new ItemNormaService();
                        $itemNorma = $itemNormaService->create($requestItemNorma);
 
                        if ($value[2] != '') {
                            $requestCheckList = [
                                "item_norma_id" => $itemNorma->id,
                                "descricao" => $value[2]
                            ];
                            $checkListItemNormaService = new CheckListItemNormaService();
                            $checkListItemNormaService->create($requestCheckList);
                        }
                    }
                }

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
                'descricao'          => empty($request->get('idNorma')) ? 'required|string|min:5|max:100|unique:docs_norma,descricao' : 'required|string|min:5|max:100|unique:docs_norma,descricao,' . $request->idNorma,
                'orgaoRegulador'     => 'required|numeric',
                'cicloAuditoria'     => 'required|numeric',
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
            "data_acreditacao"      => $request->get('dataAcreditacao') ?? null,
        ];
    }

    
}
