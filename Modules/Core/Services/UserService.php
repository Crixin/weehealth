<?php

namespace Modules\Core\Services;

use App\Classes\Helper;
use Modules\Core\Model\User;
use App\Services\ValidacaoService;
use Exception;
use Illuminate\Support\Facades\DB;
use Modules\Core\Repositories\UserRepository;
use Modules\Docs\Repositories\AgrupamentoUserDocumentoRepository;
use Modules\Docs\Repositories\UserEtapaDocumentoRepository;
use Modules\Docs\Services\AgrupamentoUserDocumentoService;
use Modules\Docs\Services\UserEtapaDocumentoService;

class UserService
{

    private $rules;
    private $userRepository;
    protected $userEtapaDocumentoRepository;
    protected $agrupamentoUserDocumentoRepository;

    public function __construct()
    {
        $perfil = new User();
        $this->rules = $perfil->rules;
        $this->userRepository = new UserRepository();
        $this->userEtapaDocumentoRepository = new UserEtapaDocumentoRepository();
        $this->agrupamentoUserDocumentoRepository = new AgrupamentoUserDocumentoRepository();
    }

    /** [
     *  array documentos,
     *  int user_id,
     *  int grupo_id,
     *  int user_substituto_id
     * ] */
    public function replaceUserDoc(array $data)
    {
        try {
            DB::transaction(function () use ($data) {

                //Aprovadores
                $buscaUserEtapaDocumento = $this->userEtapaDocumentoRepository->findBy(
                    [
                        ['documento_id', '', $data['documentos'], "IN"],
                        ['user_id', '=', $data['user_id'], "AND"],
                        ['grupo_id', '=', $data['grupo_id'], "AND"]
                    ]
                );
                foreach ($buscaUserEtapaDocumento as $key => $value) {
                    $arrayUserEtapaDocumento = [];
                    $arrayUserEtapaDocumento['grupo_user_etapa'][$key] = [
                        "user_id"           => (int) $data['user_substituto_id'],
                        "grupo_id"          => $value->grupo_id,
                        "etapa_fluxo_id"    => $value->etapa_fluxo_id,
                    ];
                    $arrayUserEtapaDocumento['documento_id'] = $value->documento_id;
                    $arrayUserEtapaDocumento['documento_revisao'] = $value->documento_revisao;

                    $userEtapaDocumentoService = new UserEtapaDocumentoService();
                    if (!$userEtapaDocumentoService->delete((array) $value->id)['success']) {
                        throw new Exception("Erro ao deletar o vinculo com o usuário.", 1);
                    }

                    if (!$userEtapaDocumentoService->store($arrayUserEtapaDocumento)['success']) {
                        throw new Exception("Erro ao desvincular usuário.", 1);
                    }
                }


                //Grupo Treinamento/Divulgacao
                $buscaGrupoTreinamentoDivulgacao = $this->agrupamentoUserDocumentoRepository->findBy(
                    [
                        ['documento_id', '', $data['documentos'], "IN"],
                        ['user_id', '=', $data['user_id'], "AND"],
                        ['grupo_id', '=', $data['grupo_id'], "AND"]
                    ]
                );
                foreach ($buscaGrupoTreinamentoDivulgacao as $key => $value) {
                    $arrayAgrupamento = [];
                    $arrayAgrupamento['grupo_and_user'][$key] = [
                        "user_id"      => (int) $data['user_substituto_id'],
                        "grupo_id"     => $value->grupo_id,
                    ];
                    $arrayAgrupamento['documento_id'] = $value->documento_id;
                    $arrayAgrupamento['tipo'] = $value->tipo;

                    $agrupamentoUserDocumentoService = new AgrupamentoUserDocumentoService();

                    if (!$agrupamentoUserDocumentoService->delete((array) $value->id)['success']) {
                        throw new Exception("Erro ao deletar o vinculo com o usuário.", 1);
                    }

                    if (!$agrupamentoUserDocumentoService->store($arrayAgrupamento)['success']) {
                        throw new Exception("Erro ao desvincular usuário.", 1);
                    }
                }
            });
            return ["success" => true];
        } catch (\Throwable $th) {
            dd($th);
            Helper::setNotify("Erro ao desvincular usuário. " . __("messages.contateSuporteTecnico"), 'danger|close-circle');
            return ["success" => false, "redirect" => redirect()->back()->withInput()];
        }
    }
}
