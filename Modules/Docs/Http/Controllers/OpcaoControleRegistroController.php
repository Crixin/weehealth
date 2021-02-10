<?php

namespace Modules\Docs\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Classes\Helper;
use Modules\Core\Repositories\ParametroRepository;
use Modules\Docs\Repositories\OpcaoControleRegistroRepository;
use Illuminate\Support\Facades\{DB, Validator};
use Modules\Docs\Services\OpcaoControleRegistroService;

class OpcaoControleRegistroController extends Controller
{
    protected $opcaoControleRegistroRepository;
    protected $parametroRepository;

    public function __construct()
    {
        $this->opcaoControleRegistroRepository = new OpcaoControleRegistroRepository();
        $this->parametroRepository = new ParametroRepository();
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $opcoes = $this->opcaoControleRegistroRepository->findAll();
        return view('docs::opcao-controle-registro.index', compact('opcoes'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $buscaTipos = $this->parametroRepository->getParametro('TIPO_CONTROLE_REGISTRO');
        $tipos = json_decode($buscaTipos);
        return view('docs::opcao-controle-registro.create', compact('tipos'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $opcaoControleRegistroService = new OpcaoControleRegistroService();
        $montaRequest = $this->montaRequest($request);
        $reponse = $opcaoControleRegistroService->store($montaRequest);

        if (!$reponse['success']) {
            return $reponse['redirect'];
        } else {
            Helper::setNotify('Nova opção criada com sucesso!', 'success|check-circle');
            return redirect()->route('docs.opcao-controle');
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
        $opcao = $this->opcaoControleRegistroRepository->find($id);

        $buscaTipos = $this->parametroRepository->getParametro('TIPO_CONTROLE_REGISTRO');
        $tipos = json_decode($buscaTipos);

        return view('docs::opcao-controle-registro.edit', compact('opcao', 'tipos'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request)
    {
        $opcaoControleRegistroService = new OpcaoControleRegistroService();
        $montaRequest = $this->montaRequest($request);
        $reponse = $opcaoControleRegistroService->update($montaRequest);

        if (!$reponse['success']) {
            return $reponse['redirect'];
        } else {
            Helper::setNotify('Opção atualizada com sucesso!', 'success|check-circle');
            return redirect()->route('docs.opcao-controle');
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
                $this->opcaoControleRegistroRepository->delete($id);
            });
            return response()->json(['response' => 'sucesso']);
        } catch (\Exception $th) {
            return response()->json(['response' => 'erro']);
        }
    }

    public function montaRequest(Request $request)
    {
        $retorno =  [
            "descricao"             => $request->get('descricao'),
            "campo_id"              => $request->get('tipoControle'),
            "ativo"                 => $request->get('ativo') == 1 ? true : false,
        ];

        if ($request->idOpcaoControle) {
            $retorno['id'] = $request->idOpcaoControle;
        }

        return $retorno;
    }
}
