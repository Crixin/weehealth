<?php

namespace Modules\Core\Services;

use App\Classes\Helper;
use Modules\Core\Model\Grupo;
use App\Services\ValidacaoService;
use Illuminate\Support\Facades\DB;
use Modules\Core\Repositories\{GrupoRepository, ParametroRepository};
use Modules\Docs\Repositories\{AgrupamentoUserDocumentoRepository, UserEtapaDocumentoRepository};

class GrupoService
{
    private $rules;
    private $grupoRepository;
    private $parametroRepository;
    private $agrupamentoUserDocumentoRepository;
    private $userEtapaDocumentoRepository;

    public function __construct(Grupo $grupo, GrupoRepository $grupoRepository)
    {
        $this->rules = $grupo->rules;
        $this->grupoRepository = $grupoRepository;
    }

    
    public function delete($data)
    {
        try {
            DB::beginTransaction();
            //FORCE TO CHECK FOREING KEY
            $this->grupoRepository->forceDelete($data);

            DB::rollBack();
            
            $this->grupoRepository->delete($data);
            DB::commit();

            Helper::setNotify('Grupo deletado com sucesso!', 'success|check-circle');
            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->failDeleteGroup($data);
            return false;
        }
    }


    private function failDeleteGroup($grupo)
    {
        /*         $agrupamento = $this->agrupamentoUserDocumentoRepository->findOneBy(
            [
                ['grupo_id', '=', $grupo],
                ]
            );
            $etapa = $this->userEtapaDocumentoRepository->findOneBy(
                [
                    ['grupo_id', '=', $grupo],
                    ]
                ); */
                

        Helper::setNotify("O grupo possui vínculos, impossível excluir sem removê-los.", 'danger|close-circle');
        return;
    }
}
