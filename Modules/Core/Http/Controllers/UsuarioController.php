<?php

namespace Modules\Core\Http\Controllers;

use App\Classes\Helper;
use Modules\Core\Model\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Core\Repositories\{UserRepository, PerfilRepository};

class UsuarioController extends Controller
{

    protected $userRepository;

    public function __construct(UserRepository $user, PerfilRepository $perfil)
    {
        $this->userRepository = $user;
        $this->perfilRepository = $perfil;
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

        $usuario = $this->userRepository->find($_id);
        return view('core::usuario.update', compact('usuario', 'perfis'));
    }


    public function updateUser(Request $request)
    {
        $arrRegras = array('name' => 'required|string|max:255', 'perfil' => 'required|numeric');
        $usuario = User::find($request->get('idUsuario'));

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

        if ($request->foto) {
            $mimeType = $request->file('foto')->getMimeType();
            $imageBase64 = base64_encode(file_get_contents($request->file('foto')->getRealPath()));
            $imageBase64 = 'data:' . $mimeType . ';base64,' . $imageBase64;
            $usuario->foto = $imageBase64;
        }

        $usuario->name = $request->get('name');
        $usuario->username = $request->get('username');
        $usuario->email = $request->get('email');
        $usuario->perfil_id = $request->get('perfil');
        $usuario->save();

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

        $usuario = User::find($request->get('idUsuario'));
        $usuario->password = bcrypt($request->get('password'));
        $usuario->save();

        Helper::setNotify('Senha alterada com sucesso!', 'success|check-circle');
        return redirect()->back()->withInput();
    }
}
