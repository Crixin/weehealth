<?php

namespace Modules\Portal\Http\Controllers;

use Illuminate\Http\Request;
use App\Classes\{RESTServices, Helper};
use App\Mail\Dossie;
use Illuminate\Support\Facades\{DB};
use Modules\Core\Http\Controllers\JobController;
use Modules\Core\Http\Controllers\Auth\JWTController;
use Modules\Portal\Repositories\{
    EmpresaProcessoRepository,
    DashboardRepository,
    DossieRepository,
    EmpresaProcessoGrupoRepository,
    EmpresaGrupoRepository,
    EmpresaUserRepository,
    ProcessoRepository
};
use Modules\Core\Repositories\{ParametroRepository};

class AjaxController extends Controller
{
    protected $dashboardRepository;

    public function __construct()
    {
        $this->dashboardRepository = new DashboardRepository();
    }


    public function deleteLinkEnterpriseGroup(Request $request)
    {
        $_idEmpresa = $request->empresa_id;
        $_idEmpresaGrupo = $request->grupo_id;
        try {
            $empresaGrupo = new EmpresaGrupoRepository();
            $empresaGrupo->delete($_idEmpresaGrupo);
            return response()->json(['response' => 'sucesso']);
        } catch (\Exception $th) {
            return response()->json(['response' => 'erro']);
        }
    }

    public function updateLinkEnterpriseGroup(Request $_request)
    {
        $empresaGrupoRepository = new EmpresaGrupoRepository();
        $empresaGrupo = $empresaGrupoRepository->find($_request->get('idVinculo'));
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
            $empresaProcessoRepository = new EmpresaProcessoRepository();
            $empresaProcessoRepository->delete($_idEmpresaProcesso);
            return response()->json(['response' => 'sucesso']);
        } catch (\Exception $th) {
            return response()->json(['response' => 'erro']);
        }
    }

    public function deleteLinkEnterpriseUser(Request $request)
    {
        $_idEmpresaUsuario = $request->vinculo_id;
        try {
            $empresaUserRepository = new EmpresaUserRepository();
            $empresaUserRepository->delete($_idEmpresaUsuario);
            return response()->json(['response' => 'sucesso']);
        } catch (\Exception $th) {
            return response()->json(['response' => 'erro']);
        }
    }

    public function updateLinkEnterpriseUser(Request $_request)
    {
        $empresaUserRepository = new EmpresaUserRepository();
        $empresaUser = $empresaUserRepository->find($_request->get('idVinculo'));
        try {
            $empresaUserRepository->update(
                [$_request->get('coluna') => $_request->get('valor')],
                $_request->get('idVinculo')
            );
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
            $processoRepository = new ProcessoRepository();
            $processoRepository->delete($_id);
            return response()->json(['response' => 'sucesso']);
        } catch (\Exception $th) {
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
            $corpo = new Dossie($token);
            $send->enqueue($emailsToResend, $corpo);

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
