<?php

namespace Modules\Core\Http\Controllers\Auth;

use Modules\Core\Model\User;
use Illuminate\Http\Request;
use Modules\Core\Repositories\PerfilRepository;
use Modules\Core\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = 'usuario';
    protected $perfilRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(PerfilRepository $perfil)
    {
        //Comentando para não exigir que o usuário não esteja autenticado para acessar a rota para inserir um novo usuário
        $this->perfilRepository = $perfil;
    }


    public function showRegistrationForm()
    {
        $perfis = $this->perfilRepository->findAll();
        return view('core::auth.register', compact('perfis'));
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:20|unique:core_users',
            'email' => 'required|string|email|max:255|unique:core_users',
            'password' => 'required|string|min:6|confirmed',
            'foto' => 'image|mimes:jpeg,png,jpg',
            'perfil' => 'required|numeric',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $createUser = [
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'utilizar_permissoes_nivel_usuario' => false,
            'password' => bcrypt($data['password']),
            'administrador' => false,
            'perfil_id' => $data['perfil'],
        ];

        if (!empty($data['foto'])) {
            $mimeType = $data['foto']->getMimeType();
            $imageBase64 = base64_encode(file_get_contents($data['foto']->getRealPath()));
            $imageBase64 = 'data:' . $mimeType . ';base64,' . $imageBase64;
            $createUser['foto'] = $imageBase64;
        }

        return User::create($createUser);
    }

    /**
     * Handle a registration request for the application.
     *
     * Sobrescrevendo o método de registro padrão de usuário para que seja possível desabilitar o 'login automático'.
     * A linha `$this->guard()->login($user);` era a responsável por fazer esse login e, por isso, foi comentada!
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {

        $this->validator($request->all())->validate();
        event(new Registered($user = $this->create($request->all())));

        // $this->guard()->login($user);

        return $this->registered($request, $user)
            ?: redirect($this->redirectPath())->with(['message' => 'Novo usuário criado com sucesso!', 'style' => 'success|check-circle']);
    }
}
