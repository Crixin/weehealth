<?php

namespace Modules\Core\Http\Controllers;

use Illuminate\Http\Request;
use App\Classes\{Constants, GEDServices, RESTServices, Helper};
use Illuminate\Support\Facades\{Auth, DB};
use App\Http\Controllers\JobController;
use App\Http\Controllers\Auth\JWTController;
use Modules\Core\Model\{Empresa, Grupo, Parametro, User, Setup};
use Modules\Core\Services\{GrupoService};
use Modules\Core\Repositories\{EmpresaRepository, ParametroRepository, UserRepository, SetupRepository};

class AjaxController extends Controller
{

    protected $empresaRepository;
    protected $paramentroRepository;
    protected $userRepository;
    protected $setupRepository;
    protected $grupoService;

    public function __construct(EmpresaRepository $empresaRepository, ParametroRepository $paramentroRepository, UserRepository $userRepository, SetupRepository $setupRepository, GrupoService $grupoService)
    {
        $this->empresaRepository = $empresaRepository;
        $this->paramentroRepository = $paramentroRepository;
        $this->userRepository = $userRepository;
        $this->setupRepository = $setupRepository;
        $this->grupoService = $grupoService;
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
        $user = User::find($_request->get('idUsuario'));
        $valor = $_request->get('valor');
        try {
            // Se o valor for verdadeiro, quer dizer que o usuário deseja sobescrever as permissões dos grupos que ele pertence e considerar apenas os vínculos diretos entre USUÁRIO e EMPRESA
            if ($valor == "true") {
                foreach ($user->coreGroups as $key => $value) {
                    $value->pivot->delete();
                }

                $user->utilizar_permissoes_nivel_usuario = true;
            } else {
                $user->utilizar_permissoes_nivel_usuario = false;
            }
            $user->save();
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

        $user  = User::find($_request->idUsuario);
        $valor = $_request->valor;
        try {
            if ($valor == "true") {
                $user->administrador = true;
            } else {
                $user->administrador = false;
            }
            $user->save();
            return response()->json(['response' => 'sucesso']);
        } catch (\Throwable $th) {
            return response()->json(['response' => 'erro']);
        }
    }


    // CONFIGURAÇÕES
    public function updateParamValue(Request $_request)
    {
        $param = Parametro::find($_request->get('parametro_id'));
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
        $param = Parametro::find($_request->get('parametro_id'));
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
        $setup = Setup::find(1);
        try {
            $setup[$_request->get('coluna')] = $_request->get('valor');
            $setup->save();
            return response()->json(['response' => 'sucesso']);
        } catch (\Throwable $th) {
            return response()->json(['response' => 'erro']);
        }
    }
}
