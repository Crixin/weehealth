<?php

namespace Modules\Portal\Http\Controllers;

use App\Classes\Helper;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Core\Model\{Empresa};
use Modules\Core\Repositories\EmpresaRepository;
use Modules\Core\Repositories\UserRepository;
use Modules\Portal\Model\{EmpresaUser};
use Modules\Portal\Repositories\EmpresaUserRepository;

class EmpresaUserController extends Controller
{
    protected $empresaRepository;
    protected $empresaUserRepository;
    protected $userRepository;


    public function __construct(EmpresaRepository $empresaRepository, EmpresaUserRepository $empresaUserRepository, UserRepository $userRepository)
    {
        $this->empresaRepository = $empresaRepository;
        $this->empresaUserRepository = $empresaUserRepository;
        $this->userRepository = $userRepository;
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

        $usuariosJaVinculados = $this->empresaUserRepository->findBy(
            [
                ['empresa_id', '=', $empresa->id]
            ]
        );

        $idUsuariosJaVinculados = $usuariosJaVinculados->pluck('user_id');
        $usuariosRestantes = $this->userRepository->findBy(
            [
                ['id','',$idUsuariosJaVinculados,'NOTIN']
            ],
            [],
            [
                ["nome","asc"]
            ]
        );

        return view('portal::empresaUser.usuarios_vinculados', compact('empresa', 'usuariosJaVinculados', 'usuariosRestantes'));
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
        $empresa = Empresa::find($request->get('idEmpresa'));
        if ($request->usuarios_empresa !== null) {
            foreach ($request->get('usuarios_empresa') as $key => $value) {
                $eu = new EmpresaUser();
                $eu->permissao_visualizar = true;
                $eu->permissao_impressao = false;
                $eu->permissao_download = false;
                $eu->permissao_aprovar_doc = false;
                $eu->permissao_excluir_doc = false;
                $eu->permissao_upload_doc = false;
                $eu->permissao_receber_email = false;
                $eu->empresa_id = $request->get('idEmpresa');
                $eu->user_id = $value;
                $eu->save();
            }
        }

        Helper::setNotify('Usuários vinculados à empresa ' . $empresa->nome . ' atualizados com sucesso!', 'success|check-circle');
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
