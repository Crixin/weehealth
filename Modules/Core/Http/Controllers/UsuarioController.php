<?php

namespace Modules\Core\Http\Controllers;

use App\Classes\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Validator};
use Modules\Core\Model\User;
use Modules\Core\Repositories\{UserRepository, PerfilRepository, SetorRepository};

class UsuarioController extends Controller
{

    protected $userRepository;
    protected $setorRepository;

    public function __construct(UserRepository $user, PerfilRepository $perfil, SetorRepository $setorRepository)
    {
        $this->userRepository = $user;
        $this->perfilRepository = $perfil;
        $this->setorRepository = $setorRepository;
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
            $arrRegras['username'] = 'required|string|max:20|unique:users';
        }

        if ($request->get('email') != $usuario->email) {
            $arrRegras['email'] = 'required|string|email|max:255|unique:users';
        }

        $arrRegras['foto'] = 'image|mimes:jpeg,png,jpg';

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
}
