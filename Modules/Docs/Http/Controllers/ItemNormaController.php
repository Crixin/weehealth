<?php

namespace Modules\Docs\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Docs\Repositories\ItemNormaRepository;
use Modules\Docs\Repositories\NormaRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Classes\Helper;

class ItemNormaController extends Controller
{
    protected $normaRepository;
    protected $itemNormaRepository;

    public function __construct(NormaRepository $normaRepository, ItemNormaRepository $itemNormaRepository)
    {
        $this->normaRepository = $normaRepository;
        $this->itemNormaRepository = $itemNormaRepository;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index($id)
    {
        $norma  = $this->normaRepository->find($id);

        $itens = $this->itemNormaRepository->findBy(
            [
                ['norma_id', '=', $id]
            ]
        );

        return view('docs::item-norma.index', compact('norma', 'itens'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create($id)
    {
        $norma  = $this->normaRepository->find($id);
        return view('docs::item-norma.create', compact('norma'));
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
                $this->itemNormaRepository->create($cadastro);
            });

            Helper::setNotify('Novo item da norma criado com sucesso!', 'success|check-circle');
            return redirect()->route('docs.norma.item-norma', ['norma_id' => $id]);
        } catch (\Throwable $th) {
            Helper::setNotify('Um erro ocorreu ao gravar o item da norma', 'danger|close-circle');
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
    public function edit($id_norma, $id)
    {
        $itemNormaEdit = $this->itemNormaRepository->find($id);

        return view('docs::item-norma.edit', compact('itemNormaEdit'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $error = $this->validador($request);
        if ($error) {
            return redirect()->back()->withInput()->withErrors($error);
        }

        $idItemNorma = $request->get('idItemNorma');
        $idNorma = $request->get('idNorma');

        $update  = $this->montaRequest($request, $idNorma);
        try {
            DB::transaction(function () use ($update, $idItemNorma) {
                $this->itemNormaRepository->update($update, $idItemNorma);
            });

            Helper::setNotify('Informações do item da norma atualizadas com sucesso!', 'success|check-circle');
        } catch (\Throwable $th) {
            Helper::setNotify('Um erro ocorreu ao atualizar o item da norma', 'danger|close-circle');
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
                $this->itemNormaRepository->delete($id);
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
                'descricao'   => 'required|string|min:5|max:300',
                'numero'      => 'required|string'
            ]
        );

        if ($validator->fails()) {
            return $validator;
        }

        return false;
    }

    public function montaRequest(Request $request, $id_norma)
    {
        return [
            "norma_id"        => $id_norma,
            "descricao"       => $request->get('descricao'),
            "numero"          => $request->get('numero')
        ];
    }
}
