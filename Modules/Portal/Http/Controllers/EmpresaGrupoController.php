<?php

namespace Modules\Portal\Http\Controllers;

use App\Classes\Helper;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Core\Repositories\{EmpresaRepository, GrupoRepository};
use Modules\Portal\Repositories\EmpresaGrupoRepository;

class EmpresaGrupoController extends Controller
{
    protected $empresaRepository;
    protected $grupoRepository;
    protected $empresaGrupoRepository;

    public function __construct(
        EmpresaRepository $empresaRepository,
        GrupoRepository $grupoRepository,
        EmpresaGrupoRepository $empresaGrupoRepository
    )
    {
        $this->empresaRepository = $empresaRepository;
        $this->grupoRepository   = $grupoRepository;
        $this->empresaGrupoRepository = $empresaGrupoRepository;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('portal::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create($_id)
    {
        $empresa = $this->empresaRepository->find($_id);
        $gruposJaVinculados = $this->empresaGrupoRepository->findBy(
            [
                ['empresa_id', '=', $empresa->id]
            ]
        );
        $idGruposJaVinculados = $gruposJaVinculados->pluck('grupo_id');
        $gruposRestantes = $this->grupoRepository->findBy(
            [
                ['id', '', $idGruposJaVinculados, 'NOTIN']
            ],
            [],
            ['nome', 'asc']
        );
        return view('portal::empresaGrupo.grupos_vinculados', compact(
            'empresa',
            'gruposJaVinculados',
            'gruposRestantes'
        ));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('portal::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('portal::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request)
    {
        $empresa = $this->empresaRepository->find($request->get('idEmpresa'));
        foreach ($request->get('grupos_empresa') as $key => $value) {
            $eg = $this->empresaGrupoRepository->create(
                [
                    "permissao_visualizar" => true,
                    "permissao_impressao"  => false,
                    "permissao_download"   => false,
                    "permissao_aprovar_doc"=> false,
                    "permissao_excluir_doc"=> false,
                    "permissao_upload_doc" => false,
                    "permissao_receber_email" => false,
                    "empresa_id" => $request->get('idEmpresa'),
                    "grupo_id" => $value
                ]
            );
        }
        Helper::setNotify('Grupos vinculados à empresa ' . $empresa->nome . ' atualizados com sucesso!', 'success|check-circle');
        return redirect()->back()->withInput();
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
