<?php

namespace Modules\Core\Services;

use App\Classes\Helper;
use Modules\Core\Model\GrupoUser;
use App\Services\ValidacaoService;
use Illuminate\Support\Facades\DB;
use Modules\Core\Repositories\{GrupoUserRepository, ParametroRepository};
use Modules\Docs\Repositories\{AgrupamentoUserDocumentoRepository, UserEtapaDocumentoRepository};

class GrupoUserService
{
    private $rules;
    private $grupoUserRepository;
    private $parametroRepository;
    private $agrupamentoUserDocumentoRepository;
    private $userEtapaDocumentoRepository;

    public function __construct(
        GrupoUser $grupoUser,
        GrupoUserRepository $grupoUserRepository,
        ParametroRepository $parametroRepository,
        AgrupamentoUserDocumentoRepository $agrupamentoUserDocumentoRepository,
        UserEtapaDocumentoRepository $userEtapaDocumentoRepository
    ) {
        $this->rules = $grupoUser->rules;
        $this->grupoUserRepository = $grupoUserRepository;
        $this->parametroRepository = $parametroRepository;
        $this->agrupamentoUserDocumentoRepository = $agrupamentoUserDocumentoRepository;
        $this->userEtapaDocumentoRepository = $userEtapaDocumentoRepository;
    }


    public function store(array $data)
    {
        try {
            DB::beginTransaction();

            $gruposUserDelete = $this->grupoUserRepository->findBy(
                [
                    ['grupo_id', "=", $data['grupo_id']],
                    ['user_id', "", $data['user_id'] ?? [] , "NOTIN"]
                ]
            )->pluck('id')->toArray();

            if (!$this->delete($gruposUserDelete)) {
                throw new \Exception('Falha da deleção dos registros');
            }

            foreach ($data['user_id'] ?? [] as $key => $user) {
                $inserir = [
                    'user_id' => $user,
                    'grupo_id' => $data['grupo_id']
                ];

                $validacao = new ValidacaoService($this->rules, $inserir);
                $errors = $validacao->make();

                if ($errors) {
                    DB::rollBack();
                    return redirect()->back()->withErrors($errors)->withInput();
                }
                $this->grupoUserRepository->firstOrCreate($inserir);
            }

            $idPerfilElaborador = $this->parametroRepository->findOneBy(
                [
                    ['identificador_parametro', '=', "PERFIL_ELABORADOR"]
                ]
            );

            $idPerfilElaborador = $idPerfilElaborador->valor_usuario ?: $idPerfilElaborador->valor_padrao;

            $elaboradoresGrupo = $this->grupoUserRepository->findOneBy(
                [
                    ['grupo_id', '=', $data['grupo_id']],
                    ['perfil_id', '=', $idPerfilElaborador, 'HAS', 'coreUser'],
                ],
            );

            if (!$elaboradoresGrupo) {
                throw new \Exception('Sem elaborador');
            }

            DB::commit();
            Helper::setNotify(__("messages.grupoUser.storeSuccess"), 'success|check-circle');
            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            Helper::setNotify(__("messages.grupoUser.storeFail"), 'danger|close-circle');
            return false;
        }
    }


    public function delete($data)
    {
        try {
            DB::beginTransaction();
            
            //FORCE TO CHECK FOREING KEY
            DB::beginTransaction();
            $this->grupoUserRepository->forceDelete($data, "id");
            DB::rollBack();

            $this->grupoUserRepository->delete($data, "id");
            DB::commit();

            Helper::setNotify(__("messages.grupoUser.deleteSucess"), 'success|check-circle');
            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            Helper::setNotify(__("messages.grupoUser.deleteFail"), 'danger|close-circle');
            return false;
        }
    }
}
