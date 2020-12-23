<?php

namespace Modules\Docs\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Docs\Repositories\CheckListItemNormaRepository;
use Modules\Docs\Repositories\ItemNormaRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Classes\Helper;

class CheckListItemNormaController extends Controller
{
    protected $checkListItemNormaRepository;
    protected $itemNormaRepository;

    public function __construct(CheckListItemNormaRepository $checkListItemNormaRepository, ItemNormaRepository $itemNormaRepository)
    {
        $this->checkListItemNormaRepository = $checkListItemNormaRepository;
        $this->itemNormaRepository = $itemNormaRepository;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request)
    {
        $itemNorma  = $this->itemNormaRepository->find($request->item_norma_id);
        $checks = $this->checkListItemNormaRepository->findBy(
            [
                ['item_norma_id','=', $request->item_norma_id]
            ],
            []
        );

        return view('docs::check-list.index', compact('checks', 'itemNorma'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create(Request $request)
    {
        $itemNorma  = $this->itemNormaRepository->find($request->item_norma_id);
 
        return view('docs::check-list.create', compact('itemNorma'));
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

        $cadastro = $this->montaRequest($request, $request->item_norma_id);
        try {
            DB::transaction(function () use ($cadastro) {
                $this->checkListItemNormaRepository->create($cadastro);
            });

            Helper::setNotify('Novo check list criado com sucesso!', 'success|check-circle');
            return redirect()->route('docs.norma.item-norma.check-list', ['norma_id' => $request->norma_id, 'item_norma_id' => $request->item_norma_id]);
        } catch (\Throwable $th) {
            Helper::setNotify('Um erro ocorreu ao gravar o check list', 'danger|close-circle');
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
    public function edit($id_norma, $id_item_norma, $id)
    {
        $checkList  = $this->checkListItemNormaRepository->find($id);
        return view('docs::check-list.edit', compact('checkList'));
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

        $idCheckList = $request->get('idCheckList');
        $itemNormaId = $request->item_norma_id;

        $update  = $this->montaRequest($request, $itemNormaId);
        try {
            DB::transaction(function () use ($update, $idCheckList) {
                $this->checkListItemNormaRepository->update($update, $idCheckList);
            });

            Helper::setNotify('Informações do check list atualizadas com sucesso!', 'success|check-circle');
        } catch (\Throwable $th) {
            Helper::setNotify('Um erro ocorreu ao atualizar o check list', 'danger|close-circle');
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
                $this->checkListItemNormaRepository->delete($id);
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
                'descricao'  => 'required|string|min:5|max:500' ,
            ]
        );

        if ($validator->fails()) {
            return $validator;
        }

        return false;
    }

    public function montaRequest(Request $request, $item_norma_id)
    {
        return [
            "item_norma_id"   => $item_norma_id,
            "descricao"       => $request->get('descricao'),
        ];
    }
}
