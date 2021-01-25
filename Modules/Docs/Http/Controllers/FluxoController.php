<?php

namespace Modules\Docs\Http\Controllers;

use App\Classes\Helper;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\Core\Repositories\{GrupoRepository, PerfilRepository};
use Modules\Docs\Repositories\FluxoRepository;

class FluxoController extends Controller
{
    protected $fluxoRepository;
    protected $grupoRepository;
    protected $perfilRepository;

    public function __construct(FluxoRepository $fluxoRepository, GrupoRepository $grupoRepository, PerfilRepository $perfilRepository)
    {
        $this->fluxoRepository = $fluxoRepository;
        $this->grupoRepository = $grupoRepository;
        $this->perfilRepository = $perfilRepository;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $fluxos = $this->fluxoRepository->findBy(
            [],
            [],
            [
                ['nome','ASC']
            ]
        );

        return view('docs::fluxo.index', compact('fluxos'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $buscaGrupos = $this->grupoRepository->findAll();
        $grupos = array_column(json_decode(json_encode($buscaGrupos), true), 'nome', 'id');

        $buscaPerfil = $this->perfilRepository->findAll();
        $perfis = array_column(json_decode(json_encode($buscaPerfil), true), 'nome', 'id');

        return view('docs::fluxo.create', compact('grupos', 'perfis'));
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
            DB::transaction(function () use ($cadastro) {
                $this->fluxoRepository->create($cadastro);
            });

            Helper::setNotify('Novo fluxo criado com sucesso!', 'success|check-circle');
            return redirect()->route('docs.fluxo');
        } catch (\Throwable $th) {
            Helper::setNotify('Um erro ocorreu ao gravar o fluxo', 'danger|close-circle');
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
        $fluxo = $this->fluxoRepository->find($id);

        $buscaGrupos = $this->grupoRepository->findAll();
        $grupos = array_column(json_decode(json_encode($buscaGrupos), true), 'nome', 'id');

        $buscaPerfil = $this->perfilRepository->findAll();
        $perfis = array_column(json_decode(json_encode($buscaPerfil), true), 'nome', 'id');

        return view('docs::fluxo.edit', compact('fluxo', 'grupos', 'perfis'));
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

        $fluxo = $request->get('idFluxo');
        $update  = self::montaRequest($request);
        try {
            DB::transaction(function () use ($update, $fluxo) {
                $this->fluxoRepository->update($update, $fluxo);
            });

            Helper::setNotify('Informações do fluxo atualizadas com sucesso!', 'success|check-circle');
        } catch (\Throwable $th) {
            Helper::setNotify('Um erro ocorreu ao atualizar o fluxo', 'danger|close-circle');
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
                $this->fluxoRepository->delete($id);
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
                'nome'               => empty($request->get('idFluxo')) ? 'required|string|min:5|max:100|unique:docs_fluxo,nome' : 'required|string|min:5|max:100|unique:docs_fluxo,nome,' . $request->idFluxo,
                'versao'             => 'required|',
                'descricao'          => 'required|string|min:5|max:200',
                'grupo'              => 'required|numeric',
                'perfil'             => 'required|numeric'
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
            "nome"      => $request->get('nome'),
            "versao"    => $request->get('versao'),
            "descricao" => $request->get('descricao'),
            "perfil_id" => $request->get('perfil'),
            "grupo_id"  => $request->get('grupo'),
            "ativo"     => $request->get('ativo') == 1 ? true : false,
        ];
    }
}
