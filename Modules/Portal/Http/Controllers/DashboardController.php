<?php

namespace Modules\Portal\Http\Controllers;

use App\Classes\Helper;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Repositories\{GrupoUserRepository};
use Modules\Portal\Repositories\{
    DashboardRepository,
    EmpresaGrupoRepository,
    EmpresaUserRepository,
    EmpresaProcessoRepository,
    UserDashboardRepository
};
use Modules\Core\Repositories\{UserRepository,  EmpresaRepository};
use App\Classes\RESTServices;
use App\Classes\Constants;

class DashboardController extends Controller
{
    protected $dashboardRepository;
    protected $usuarioRepository;
    protected $grupoUserRepository;
    protected $empresaGrupoRepository;
    protected $empresaUserRepository;
    protected $empresaRepository;
    protected $empresaProcessoRepository;
    protected $userDashboardRepository;
    protected $userRepository;

    private $REST;
    private $const;

    public function __construct()
    {
        $this->dashboardRepository = new DashboardRepository();
        $this->grupoUserRepository = new GrupoUserRepository();
        $this->empresaGrupoRepository = new EmpresaGrupoRepository();
        $this->empresaUserRepository = new EmpresaUserRepository();
        $this->empresaRepository = new EmpresaRepository();
        $this->empresaProcessoRepository = new EmpresaProcessoRepository();
        $this->userDashboardRepository = new UserDashboardRepository();
        $this->userRepository = new UserRepository();

        $this->REST = new RESTServices();
        $this->const = new Constants();
    }

    public function index()
    {
        $gedUrl = env('GED_URL');
        $gedUserToken = env('GED_USER_TOKEN');
        $dashboards = $this->dashboardRepository->findAll();

        return view('portal::dashboard.index', compact('dashboards', 'gedUserToken', 'gedUrl'));
    }

    public function newDashboard()
    {
        $empresas = Helper::getProcessesByUserAccess();
        $tiposIndicesGED = $this->const::$OPTIONS_TYPE_INDICES_GED;
        return view('portal::dashboard.create', compact('empresas', 'tiposIndicesGED'));
    }

    public function saveDashboard(Request $request)
    {
        $validador = self::validador($request);
        if ($validador == 1) {
            return redirect()->back()->withInput();
        }

        $montaCreate = self::montaRequest($request);
        DB::beginTransaction();
        try {
            $this->dashboardRepository->create($montaCreate);
            DB::commit();
            Helper::setNotify('Novo dashboard criado com sucesso!', 'success|check-circle');
        } catch (\Throwable $th) {
            DB::rollback();
            Helper::setNotify('Um erro ocorreu ao gravar o registro', 'danger|close-circle');
        }
        return redirect()->route('portal.dashboards');
    }

    public function editDashboard($_id)
    {
        $dashboard = $this->dashboardRepository->find($_id);
        $numGraficos = count(json_decode($dashboard['config']));
        $empresas = Helper::getProcessesByUserAccess();
        $tiposIndicesGED = $this->const::$OPTIONS_TYPE_INDICES_GED;
        return view('portal::dashboard.update', compact('dashboard', 'numGraficos', 'tiposIndicesGED', 'empresas'));
    }

    public function updateDashboard(Request $request)
    {
        $validador = self::validador($request);
        if ($validador == 1) {
            return redirect()->back()->withInput();
        }
        $montaUpdate = self::montaRequest($request);
        DB::beginTransaction();
        try {
            $this->dashboardRepository->update($montaUpdate, $request->get('idDashboard'));
            DB::commit();
            Helper::setNotify('Informações do dashboard alteradas com sucesso!', 'success|check-circle');
        } catch (\Throwable $th) {
            DB::rollback();
            Helper::setNotify('Um erro ocorreu ao alterar o registro', 'danger|close-circle');
        }
        return redirect()->back()->withInput();
    }

    public function linkedUsers($_id)
    {
        $dashboard = $this->dashboardRepository->find($_id);
        $usuarios = $this->userRepository->findAll([], [['name', 'asc']]);
        $usuariosVinculados = $this->userDashboardRepository->findBy(
            [
                ['dashboard_id', '=', $_id]
            ],
            ['coreUsers']
        )->toArray();
        $usuariosVinculados = array_column($usuariosVinculados, 'user_id');
        
        return view('portal::dashboard.usuarios_vinculados', compact('dashboard', 'usuarios', 'usuariosVinculados'));
    }

    public function updateLinkedUsers(Request $request)
    {
        $dashboard = $this->dashboardRepository->find($request->idDashboard);

        $usuarioDashboard = $request->usuarios_dashboard ?? [];

        $this->userDashboardRepository = new UserDashboardRepository();
        $buscaUsuarioDashboard = $this->userDashboardRepository->findBy([['dashboard_id', '=', $dashboard->id]]);

        $id_usuario = array();
        foreach ($buscaUsuarioDashboard as $key => $value) {
            array_push($id_usuario, $value['user_id']);
        }

        $diff_post  = array_diff($usuarioDashboard, $id_usuario);
        $diff_banco = array_diff($id_usuario, $usuarioDashboard);

        DB::beginTransaction();
        try {
            foreach ($diff_post as $key => $idUser) {
                $this->userDashboardRepository->create(
                    [
                        'dashboard_id' => $dashboard->id,
                        'user_id' => $idUser
                    ]
                );
            }

            foreach ($diff_banco as $key => $idUser) {
                $this->userDashboardRepository->delete(
                    [
                        ['dashboard_id', '=', $dashboard->id],
                        ['user_id', '=', $idUser]
                    ]
                );
            }

            DB::commit();
            Helper::setNotify('Usuários vinculados ao dashboard ' . $dashboard->nome . ' atualizados com sucesso!', 'success|check-circle');
        } catch (\Exception $th) {
            DB::rollback();
            Helper::setNotify('Um erro ocorreu ao alterar o registro', 'danger|close-circle');
        }
        return redirect()->back()->withInput();
    }

    public function deleteDashboard(Request $request)
    {
        $id = $request = $request->id;
        DB::beginTransaction();
        try {
            $this->dashboardRepository->delete($id);
            DB::commit();
            return response()->json(['response' => 'sucesso']);
        } catch (\Exception $th) {
            DB::rollback();
            return response()->json(['response' => 'erro']);
        }
    }


    public function view(int $_id)
    {
        $gedUrl = env('GED_URL');
        $gedUserToken = env('GED_USER_TOKEN');

        $dashes = array_column(Auth::user()->portalDashboards->toArray(), 'dashboard_id');
        //dd($dashes);

        if (!in_array($_id, $dashes)) {
            return response()->view('core::errors.403');
        }

        $configuracao = $this->dashboardRepository->find($_id);
        $configuracao = json_decode($configuracao->config);

        return view('portal::dashboard.view', compact('configuracao', 'gedUrl', 'gedUserToken'));
    }

    private function validador(Request $request)
    {
        $erro = 0;
        $validator = Validator::make($request->all(), [
            'nameDashboard' => 'required|string',
        ]);

        if ($validator->fails()) {
            Helper::setNotify($validator->messages()->first(), 'danger|close-circle');
            $erro = 1;
        }
        return $erro;
    }

    private function montaRequest(Request $request)
    {
        $monta = [
            'nome'   => $request->get('nameDashboard'),
            'config' => $request->get('saved-data')
         ];

         return $monta;
    }

    public function findDashboard(Request $request)
    {
        $id = $request->idDashboard;
        $dashboard = $this->dashboardRepository->find($id);
        return json_decode($dashboard->config);
    }
}
