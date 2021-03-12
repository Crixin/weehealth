<?php

namespace Modules\Core\Http\Controllers\Auth;

use App\Classes\Helper;
use Modules\Core\Model\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Modules\Core\Repositories\{SetorRepository, UserRepository, PerfilRepository};
use Modules\Core\Services\UserService;

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
    protected $setorRepository;
    protected $userRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct
    (
        PerfilRepository $perfil,
        SetorRepository $setorRepository,
        UserRepository $userRepository
    )
    {
        //Comentando para não exigir que o usuário não esteja autenticado para acessar a rota para inserir um novo usuário
        $this->perfilRepository = $perfil;
        $this->setorRepository = $setorRepository;
        $this->userRepository = $userRepository;
    }


    public function showRegistrationForm()
    {
        $perfis = $this->perfilRepository->findAll();
        $setores = $this->setorRepository->findAll();
        return view('core::auth.register', compact('perfis', 'setores'));
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name'     => 'required|string|max:255',
                'username' => 'required|string|max:20|unique:core_users',
                'email'    => 'required|string|email|max:255|unique:core_users',
                'password' => 'required|string|min:6|confirmed',
                'foto'     => 'image|mimes:jpeg,png,jpg',
                'perfil'   => 'required|numeric',
                'setor'    => 'required|numeric'
            ]
        );

        if ($validator->fails()) {
            return $validator;
        }
        return false;
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
            'name'          => $data['name'],
            'username'      => $data['username'],
            'email'         => $data['email'],
            'utilizar_permissoes_nivel_usuario' => false,
            'password'      => bcrypt($data['password']),
            'administrador' => false,
            'perfil_id'     => $data['perfil'],
            'setor_id'      => $data['setor']
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
        try {
            $error = $this->validator($request);
            if ($error) {
                return redirect()->back()->withInput()->withErrors($error);
            }

            DB::beginTransaction();
            event(new Registered($user = $this->create($request->all())));
            //Verifica existencia usuario no bd
            $usuario = $user->username;
            $password = $user->password;
            $busca = DB::select("SELECT count(*) as total FROM pg_roles WHERE rolname ILIKE '" . $usuario . "'");
            if ($busca[0]->total == 0) {
                $userAux = '"' . $usuario . '"';
                DB::purge(getenv('DB_CONNECTION'));
                Config::set('database.connections.pgsql.username', getenv('DB_USERNAME'));
                Config::set('database.connections.pgsql.password', getenv('DB_PASSWORD'));
                DB::reconnect(getenv('DB_CONNECTION'));
                $cria   = DB::select("CREATE ROLE $userAux WITH LOGIN NOSUPERUSER INHERIT NOCREATEDB NOCREATEROLE NOREPLICATION VALID UNTIL 'infinity' ");
                $altera = DB::unprepared("ALTER USER $userAux WITH PASSWORD '" . $password . "'");
                $setFrupo = DB::unprepared("GRANT weehealth TO $userAux ");
            }

            // $this->guard()->login($user);
            $userService = new UserService();
            $montaRequest = $this->montaRequest($request);
            $userService->store($montaRequest);

            DB::commit();
            return redirect()->route('core.usuario')->with(['message' => 'Novo usuário criado com sucesso!', 'style' => 'success|check-circle']);

        } catch (\Throwable $th) {
            DB::rollBack();
            Helper::setNotify('Um erro ocorreu ao gravar o usuario.', 'danger|close-circle');
            return redirect()->back()->withInput();
        }
    }

    
}
