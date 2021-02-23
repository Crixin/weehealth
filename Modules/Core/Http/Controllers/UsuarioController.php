<?php

namespace Modules\Core\Http\Controllers;

use App\Classes\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\{DB, Validator};
use Modules\Core\Model\User;
use Modules\Core\Repositories\{GrupoUserRepository, UserRepository, PerfilRepository, SetorRepository};
use Modules\Core\Services\UserService;
use Modules\Docs\Services\AgrupamentoUserDocumentoService;

class UsuarioController extends Controller
{

    protected $userRepository;
    protected $setorRepository;
    protected $grupoUserRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
        $this->perfilRepository = new PerfilRepository();
        $this->setorRepository = new SetorRepository();
        $this->grupoUserRepository = new GrupoUserRepository();
    }


    public function index()
    {
    /*     $usuarios = User::where('id', '!=', reset(Constants::$ARR_SUPER_ADMINISTRATORS_ID))->orderBy('name')->select('id', 'name', 'username', 'email', 'utilizar_permissoes_nivel_usuario')->get(); */
        $usuarios = $this->userRepository->findAll();
        return view('core::usuario.index', compact('usuarios'));
    }


    public function editUser($_id)
    {
        $perfis = $this->perfilRepository->findAll();
        $setores = $this->setorRepository->findAll();

        $usuario = $this->userRepository->find($_id);
        return view('core::usuario.update', compact('usuario', 'perfis', 'setores'));
    }


    public function updateUser(Request $request)
    {
        $arrRegras = array('name' => 'required|string|max:255', 'perfil' => 'required|numeric');
        $usuario = $this->userRepository->find($request->get('idUsuario'));

        if ($request->get('username') != $usuario->username) {
            $arrRegras['username'] = 'required|string|max:20|unique:core_users';
        }

        if ($request->get('email') != $usuario->email) {
            $arrRegras['email'] = 'required|string|email|max:255|unique:core_users';
        }

        if ($request->foto) {
            $arrRegras['foto'] = 'image|mimes:jpeg,png,jpg';
        }

        $validator = Validator::make($request->all(), $arrRegras);

        if ($validator->fails()) {
            Helper::setNotify($validator->messages()->first(), 'danger|close-circle');
            return redirect()->back()->withInput();
        }


        $montaUpdate = $this->montaRequest($request);
        $this->userRepository->update($montaUpdate, $request->get('idUsuario'));

        Helper::setNotify('Informações pessoais alteradas com sucesso!', 'success|check-circle');
        return redirect()->back()->withInput();
    }

    public function updateUserPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:6|confirmed'
        ]);

        if ($validator->fails()) {
            Helper::setNotify($validator->messages()->first(), 'danger|close-circle');
            return redirect()->back()->withInput();
        }

        try {
            DB::transaction(function () use ($request) {
                $usuario = User::find($request->get('idUsuario'));
                $senha = bcrypt($request->get('password'));
                $usuario->password = $senha;
                $usuario->save();
                DB::purge(getenv('DB_CONNECTION'));
                Config::set('database.connections.pgsql.username', getenv('DB_USERNAME'));
                Config::set('database.connections.pgsql.password', getenv('DB_PASSWORD'));
                DB::reconnect(getenv('DB_CONNECTION'));
                $altera = DB::unprepared("ALTER USER $usuario->username WITH PASSWORD '" . $senha . "'");
            });

            Helper::setNotify('Senha alterada com sucesso!', 'success|check-circle');
            return redirect()->back()->withInput();
        } catch (\Throwable $th) {
            Helper::setNotify('Um erro ocorreu ao alterar a senha.', 'danger|close-circle');
            return redirect()->back()->withInput();
        }
    }

    public function montaRequest(Request $request)
    {
        $retorno = [
            "name"      => $request->get('name'),
            "username" => $request->get('username'),
            "email"     => $request->get('email'),
            "perfil_id"     => $request->get('perfil'),
            "setor_id"     => $request->get('setor'),
        ];

        if ($request->foto) {
            $mimeType = $request->file('foto')->getMimeType();
            $imageBase64 = base64_encode(file_get_contents($request->file('foto')->getRealPath()));
            $imageBase64 = 'data:' . $mimeType . ';base64,' . $imageBase64;
            $retorno['foto'] = $imageBase64;
        }

        return $retorno;
    }

    public function changeUser($id)
    {
        $usuario = $this->userRepository->find($id);
        $modulos = ["Docs" => ["Documentos" => "Documentos"]];
        return view('core::usuario.change-user-modulo', compact('usuario', 'modulos'));
    }

    public function changeUserMod(Request $request)
    {
        $id = $request->id;
        $modulos = $request->modulos;
        switch ($modulos) {
            case 'Documentos':
                return $this->substituirModDoc($id);
                break;
        }
    }

    public function userByGroup(Request $request)
    {
        try {
            $grupo   = $request->grupo;
            $usuario = $request->usuario;
            $buscaUsuarios = $this->grupoUserRepository->findBy(
                [
                    ['grupo_id', '=', $grupo],
                    ['user_id', '!=', $usuario, "AND"]
                ]
            );

            $usuarios = [];
            foreach ($buscaUsuarios as $key => $value) {
                $usuarios[$key] = [
                    'id' => $value->coreUsers->id,
                    'nome' => $value->coreUsers->name
                ];
            }
            return response()->json(['response' => 'sucesso', 'data' => json_encode($usuarios)]);
        } catch (\Exception $th) {
            return response()->json(['response' => 'erro']);
        }
    }

    public function substituirModDoc($id)
    {
        $usuario = $this->userRepository->find($id);
        $buscaGrupos = $this->grupoUserRepository->findBy(
            [
                ['user_id', '=', $id]
            ],
            ['coreGrupo']
        );
        $grupos = [];
        foreach ($buscaGrupos as $key => $value) {
            $grupos[$value->coreGrupo->id] = $value->coreGrupo->nome;
        }
        return view('core::usuario.change-user-doc', compact('usuario', 'grupos'));
    }

    public function replaceUserDoc(Request $request)
    {
        try {
            $idUsuario = $request->idUsuario;
            $idGrupo   = $request->grupo;
            $documentos = $request->documento;
            $idUsuarioSubstituto = $request->usuario;

            $montaUpdate = [
                "documentos" => $documentos,
                "user_id" =>  $idUsuario,
                "grupo_id" => $idGrupo,
                "user_substituto_id" => $idUsuarioSubstituto
            ];

            $userService = new UserService();
            $return = $userService->replaceUserDoc($montaUpdate);

            if (!$return['success']) {
                throw new Exception("Erro ao desvincular usuário.", 1);
            }

            Helper::setNotify('Usuário substituido com sucesso!', 'success|check-circle');
            return redirect()->route('core.usuario');
        } catch (\Throwable $th) {

            Helper::setNotify("Erro ao desvincular usuário. " . __("messages.contateSuporteTecnico"), 'danger|close-circle');
            return redirect()->route('core.usuario');
        }
    }
}
