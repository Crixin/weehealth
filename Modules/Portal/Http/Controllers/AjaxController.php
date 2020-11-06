<?php

namespace Modules\Portal\Http\Controllers;

use Illuminate\Http\Request;
use App\Classes\{Constants, RESTServices, Helper};
use Illuminate\Support\Facades\{Auth, DB};
use Modules\Core\Http\Controllers\JobController;
use Modules\Core\Http\Controllers\Auth\JWTController;
use Modules\Core\Model\{Empresa, Parametro, User, Setup};
use Modules\Portal\Model\{EmpresaGrupo, EmpresaProcesso, EmpresaUser, Grupo, Processo};
use Modules\Portal\Repositories\{
    EmpresaProcessoRepository,
    DashboardRepository,
    DossieRepository,
    EmpresaProcessoGrupoRepository
};
use Modules\Core\Repositories\{ParametroRepository};

class AjaxController extends Controller
{
    protected $dashboardRepository;

    public function __construct(DashboardRepository $dashboard)
    {
        $this->dashboardRepository = $dashboard;
    }

    // EMPRESA
    public function deleteEnterprise(Request $request)
    {
        $_id = $request->empresa_id;
        try {
            Empresa::destroy($_id);
            return response()->json(['response' => 'sucesso']);
        } catch (\Exception $th) {
            return response()->json(['response' => 'erro']);
        }
    }

    public function deleteLinkEnterpriseGroup(Request $request)
    {
        $_idEmpresa = $request->empresa_id;
        $_idEmpresaGrupo = $request->grupo_id;
        try {
            EmpresaGrupo::destroy($_idEmpresaGrupo);
            return response()->json(['response' => 'sucesso']);
        } catch (\Exception $th) {
            return response()->json(['response' => 'erro']);
        }
    }

    public function updateLinkEnterpriseGroup(Request $_request)
    {
        $empresaGrupo = EmpresaGrupo::find($_request->get('idVinculo'));
        try {
            $empresaGrupo[$_request->get('coluna')] = $_request->get('valor');
            $empresaGrupo->save();
            return response()->json(['response' => 'sucesso']);
        } catch (\Exception $th) {
            return response()->json(['response' => 'erro']);
        }
    }

    public function deleteLinkEmpresaProcessoGrupo(Request $_request)
    {
        try {
            $empresaProcessoGrupoRepository = new EmpresaProcessoGrupoRepository();
            $empresaProcessoGrupoRepository->delete($_request->vinculo_id);
            return response()->json(['response' => 'sucesso']);
        } catch (\Exception $th) {
            return response()->json(['response' => 'erro']);
        }
    }

    public function deleteLinkEnterpriseProcess(Request $request)
    {
        $_idEmpresaProcesso = $request->vinculo_id;
        try {
            EmpresaProcesso::destroy($_idEmpresaProcesso);
            return response()->json(['response' => 'sucesso']);
        } catch (\Exception $th) {
            return response()->json(['response' => 'erro']);
        }
    }

    public function deleteLinkEnterpriseUser(Request $request)
    {
        $_idEmpresaUsuario = $request->vinculo_id;
        try {
            EmpresaUser::destroy($_idEmpresaUsuario);
            return response()->json(['response' => 'sucesso']);
        } catch (\Exception $th) {
            return response()->json(['response' => 'erro']);
        }
    }

    public function updateLinkEnterpriseUser(Request $_request)
    {
        $empresaUser = EmpresaUser::find($_request->get('idVinculo'));
        try {
            $empresaUser[$_request->get('coluna')] = $_request->get('valor');
            $empresaUser->save();
            return response()->json(['response' => 'sucesso']);
        } catch (\Exception $th) {
            return response()->json(['response' => 'erro']);
        }
    }

    // GRUPO
    public function deleteGroup(Request $request)
    {
        $_id = $request->grupo_id;
        try {
            Grupo::destroy($_id);
            return response()->json(['response' => 'sucesso']);
        } catch (\Exception $th) {
            return response()->json(['response' => 'erro']);
        }
    }

    public function deleteDossie(Request $_request)
    {
        try {
            $dossie = new DossieRepository();
            $dossie->delete($_request->dossie);
            return response()->json(['response' => 'sucesso']);
        } catch (\Exception $th) {
            return response()->json(['response' => 'erro']);
        }
    }

    // PROCESSO
    public function deleteProcess(Request $request)
    {
        $_id = $request->processo_id;
        try {
            Processo::destroy($_id);
            return response()->json(['response' => 'sucesso']);
        } catch (\Exception $th) {
        }
    }

    // USUÁRIO
    public function deleteUser(Request $request)
    {
        $_id = $request->id;
        try {
            User::destroy($_id);
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
                foreach ($user->groups as $key => $value) {
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

    // NOTIFICAÇÃO
    public function markAsReadNotification(Request $request)
    {
        $_id = $request->notificacao_id;
        try {
            Auth::user()->unreadNotifications->where('id', $_id)->markAsRead();
            return response()->json(['response' => 'sucesso']);
        } catch (\Exception $th) {
            return response()->json(['response' => 'erro']);
        }
    }

    #--------------------------------------------------------------------------------------------------------#
    #                                      Funções de acesso ao GED                                          #
    #                                                                                                        #
    #--------------------------------------------------------------------------------------------------------#
    // DOCUMENTO
    public function deleteDocument(Request $request)
    {
        try {
            $rest = new RESTServices();
            $result = $rest->delete(env("GED_URL") . "/documento/" . $request->documento_id);

            if (!$result['error']) {
                return response()->json(['response' => 'sucesso']);
            } else {
                return response()->json(['response' => 'erro']);
            }
        } catch (\Throwable $th) {
            return response()->json(['response' => 'erro']);
        }
    }

    public function deleteRegister(Request $request)
    {
        try {
            $rest = new RESTServices();
            $result = $rest->delete(env("GED_URL") . "/registro/" . $request->registro_id);

            if (!$result['error']) {
                return response()->json(['response' => 'sucesso']);
            } else {
                return response()->json(['response' => 'erro']);
            }
        } catch (\Throwable $th) {
            return response()->json(['response' => 'erro']);
        }
    }

    public function buscaInfoArea(Request $_request)
    {
        $rest = new RESTServices();

        return $rest->buscaInfoArea($_request->idArea, $_request->params ?? "");
    }

    public function pesquisaRegistro(Request $_request)
    {
        $rest = new RESTServices();
        return $rest->pesquisaRegistro($_request->params);
    }

    public function pesquisaDocumento(Request $_request)
    {
        $rest = new RESTServices();
        return $rest->pesquisaDocumento($_request->params);
    }

    public function getDocumento(Request $_request)
    {
        $rest = new RESTServices();
        return $rest->getDocumento($_request->id);
    }

    public function getRegistro(Request $_request)
    {
        $rest = new RESTServices();
        return $rest->getRegistro($_request->id, $_request->params ?? "");
    }

    public function getIndicesComumAreas(Request $_request)
    {
        return Helper::buscaIndicesComumAreasGED($_request->listaAreas);
    }

    public function getProcessByEnterpriseAndProcesso(Request $_request)
    {
        $repositoryEmpresaProcesso = new EmpresaProcessoRepository();
        $empresaProcesso = $repositoryEmpresaProcesso->findOneBy([
           ["empresa_id", "=", $_request->empresa],
           ["processo_id", "=", $_request->processo]
        ]);

        return $empresaProcesso;
    }


    public function resendDossie(Request $_request)
    {
        DB::beginTransaction();
        try {
            $dossieRepository = new DossieRepository();
            $parametroRepository = new ParametroRepository();

            $dias = ($parametroRepository->findOneBy([["identificador_parametro", '=', 'TIME_PRORROG_DOSSIE']]))->valor_padrao;
            $dossie = $dossieRepository->find($_request->dossie);

            $emailsToResend = [];

            $validade = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' + ' . $dias . 'days'));

            $dossieRepository->update([
                'validade' => $validade,
                'status' => "DISPONÍVEL"
            ], $dossie->id);
            DB::commit();

            foreach (unserialize($dossie->destinatarios) as $key => $destinatario) {
                if (!$destinatario['downloaded']) {
                    $emailsToResend[] = $destinatario['email'];
                }
            }

            $info = [
                'email' => $emailsToResend,
                'dossie' => $dossie->id
            ];

            $jwt = new JWTController();
            // tempo definido em dias mas precisa usar minutos
            $token = $jwt->generateToken($info, 1440 * $dias);

            $send = new JobController();

            $server = 'http' . (empty($_SERVER['HTTPS']) ? '' : 's') . '://' . $_SERVER['HTTP_HOST'];

            $send->enqueue($token, $emailsToResend, $server);

            return response()->json(['response' => 'sucesso']);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['response' => 'erro']);
        }
    }

    public function getPreFiltroProcessos(Request $_request)
    {
        try {
            $empresaProcessoGrupoRepository = new EmpresaProcessoGrupoRepository();

            $empresaProcessoGrupo = $empresaProcessoGrupoRepository->findBy(
                [
                    ["empresa_processo_id", "=", $_request->empresa_processo_id],
                    ["grupo_id", "=", $_request->grupo_id],
                ]
            );
            return response()->json(['response' => $empresaProcessoGrupo[0]->filtros ?? []]);
        } catch (\Throwable $th) {
            return response()->json(['response' => 'erro']);
        }
    }
}
