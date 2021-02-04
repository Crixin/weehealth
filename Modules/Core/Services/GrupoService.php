<?php

namespace Modules\Core\Services;

use App\Classes\Helper;
use Modules\Core\Model\Grupo;
use Illuminate\Support\Facades\DB;
use Modules\Core\Repositories\{GrupoRepository};

class GrupoService
{
    private $rules;
    private $grupoRepository;


    public function __construct()
    {
        $grupo = new Grupo();
        $this->grupoRepository = new GrupoRepository();
        $this->rules = $grupo->rules;
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
            return ["success" => true];
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->failDeleteGroup($data);
            return ["success" => false];
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
