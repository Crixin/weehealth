<?php

namespace Modules\Core\Http\Controllers;

use App\Classes\Helper;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\{DB, Validator};
use Modules\Core\Repositories\{GrupoUserRepository, UserRepository, PerfilRepository, SetorRepository};
use Modules\Core\Services\UserService;

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

    public function create()
    {
        $perfis = $this->perfilRepository->findAll();
        $setores = $this->setorRepository->findAll();
        return view('core::auth.register', compact('perfis', 'setores'));
    }

    public function store(Request $request)
    {
        $usuarioService = new UserService();
        $reponse = $usuarioService->store($request);
        if (!$reponse['success']) {
            Helper::setNotify('Um erro ocorreu criar o usuário.', 'danger|close-circle');
            return $reponse['redirect'];
        }
        Helper::setNotify('Novo usuario criado com sucesso!', 'success|check-circle');
        return redirect()->route('core.usuario');

    }


    public function edit($_id)
    {
        $perfis = $this->perfilRepository->findAll();
        $setores = $this->setorRepository->findAll();

        $usuario = $this->userRepository->find($_id);
        return view('core::usuario.update', compact('usuario', 'perfis', 'setores'));
    }


    public function update(Request $request)
    {
        $usuarioService = new UserService();

        $reponse = $usuarioService->update($request, $request->idUsuario);

        if (!$reponse['success']) {
            return $reponse['redirect'];
        }

        Helper::setNotify('Usuário atualizado com sucesso!', 'success|check-circle');
        return redirect()->route('core.usuario');
    }

    public function updateUserPassword(Request $request)
    {
        $usuarioService = new UserService();
        $reponse = $usuarioService->updateUserPassword($request);

        if (!$reponse['success']) {
            return $reponse['redirect'];
        }

        Helper::setNotify('Senha do usuário atualizada com sucesso!', 'success|check-circle');
        return redirect()->route('core.usuario');
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

    public function inativateUser(Request $request)
    {
        try {
            $idUsuario = $request->id;
            $inativo   = $request->operacao == 'inativar' ? 1 : 0;
            $msg = $request->operacao == 'inativar' ? 'inativado' : 'ativado';
            $montaUpdate = [
                "inativo" => $inativo
            ];

            $userService = new UserService();
            $return = $userService->inativate($montaUpdate, $idUsuario);

            if (!$return['success']) {
                throw new Exception("Erro ao " . $request->operacao . " usuário.", 1);
            }

            //Helper::setNotify("Usuário " . $msg . " com sucesso!", 'success|check-circle');
            return response()->json(['response' => 'sucesso', 'message' => $return['message']]);
        } catch (\Throwable $th) {
            //Helper::setNotify("Erro ao " . $request->operacao . " usuário. " . __("messages.contateSuporteTecnico"), 'danger|close-circle');
            return response()->json(['response' => 'erro', 'message' => $return['message']]);
        }
    }
}
