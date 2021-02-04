<?php

namespace Modules\Core\Http\Controllers;

use Illuminate\Http\Request;
use App\Classes\{Constants};
use Modules\Core\Repositories\{EmpresaRepository, ParametroRepository, UserRepository, SetupRepository};

class AjaxController extends Controller
{

    protected $empresaRepository;
    protected $paramentroRepository;
    protected $userRepository;
    protected $setupRepository;

    public function __construct()
    {
        $this->empresaRepository = new EmpresaRepository();
        $this->paramentroRepository = new ParametroRepository();
        $this->userRepository = new UserRepository();
        $this->setupRepository = new SetupRepository();
    }

    // EMPRESA
    public function deleteEnterprise(Request $request)
    {
        $_id = $request->empresa_id;
        try {
            $this->empresaRepository->delete($_id);
            return response()->json(['response' => 'sucesso']);
        } catch (\Exception $th) {
            return response()->json(['response' => 'erro']);
        }
    }

    // USUÁRIO
    public function deleteUser(Request $request)
    {
        $_id = $request->id;
        try {
            $this->userRepository->delete($_id);
            return response()->json(['response' => 'sucesso']);
        } catch (\Exception $th) {
            return response()->json(['response' => 'erro']);
        }
    }


    public function updateUserPermissions(Request $_request)
    {
        $user = $this->userRepository->find($_request->get('idUsuario'));
        $valor = $_request->get('valor');
        try {
            // Se o valor for verdadeiro, quer dizer que o usuário deseja sobescrever as permissões dos grupos que ele pertence e considerar apenas os vínculos diretos entre USUÁRIO e EMPRESA
            $utilizar_permissoes_nivel_usuario = false;
            if ($valor == "true") {
                foreach ($user->coreGroups as $key => $value) {
                    $value->pivot->delete();
                }
                $utilizar_permissoes_nivel_usuario = true;
            } 
            $this->userRepository->update(["utilizar_permissoes_nivel_usuario" => $utilizar_permissoes_nivel_usuario], $_request->get('idUsuario'));
            return response()->json(['response' => 'sucesso']);
        } catch (\Exception $th) {
            return response()->json(['response' => 'erro']);
        }
    }


    public function updateAdministratorPermissions(Request $_request)
    {
        // Se algum super hacker alterar o HTML e enviar o id de um dos administradores fixos do sistema...
        if (in_array($_request->idUsuario, Constants::$ARR_SUPER_ADMINISTRATORS_ID)) {
            return response()->json(['response' => 'erro']);
        }

        $user  = $this->userRepository->find($_request->idUsuario);
        $valor = $_request->valor;
        try {
            $administrador = false;
            if ($valor == "true") {
                $administrador = true;
            }
            $this->userRepository->update(['administrador' => $administrador], $_request->idUsuario);
            return response()->json(['response' => 'sucesso']);
        } catch (\Throwable $th) {
            return response()->json(['response' => 'erro']);
        }
    }


    // CONFIGURAÇÕES
    public function updateParamValue(Request $_request)
    {
        $param = $this->paramentroRepository->find($_request->get('parametro_id'));
        try {
            $param[$_request->get('coluna')] = $_request->get('valor');
            $param->save();
            return response()->json(['response' => 'sucesso']);
        } catch (\Exception $th) {
            return response()->json(['response' => 'erro']);
        }
    }


    public function updateParamActiveValue(Request $_request)
    {
        $param = $this->paramentroRepository->find($_request->get('parametro_id'));
        try {
            $param[$_request->get('coluna')] = $_request->get('valor');
            $param->save();
            return response()->json(['response' => 'sucesso']);
        } catch (\Exception $th) {
            return response()->json(['response' => 'erro']);
        }
    }


    //SETUP
    public function updateSetup(Request $_request)
    {
        $setup = $this->setupRepository->find(1);
        try {
            $this->setupRepository->update([$_request->get('coluna') => $_request->get('valor')], 1);
            return response()->json(['response' => 'sucesso']);
        } catch (\Throwable $th) {
            return response()->json(['response' => 'erro']);
        }
    }
}
