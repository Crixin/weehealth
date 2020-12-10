<?php

namespace Modules\Core\Repositories;

use Illuminate\Support\Facades\Auth;
use Modules\Core\Model\Setor;
use Modules\Core\Repositories\BaseRepository;

class SetorRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new Setor();
    }

    public function getSetorUsuario($ID_SETOR_QUALIDADE)
    {
        $idUserSetor = Auth::user()->coreSetor->id;
        if ($idUserSetor != $ID_SETOR_QUALIDADE) {
            $setores[0] = $this->find($idUserSetor);
        } else {
            $setores = $this->findBy(
                [
                    ['nome', '!=', 'Sem Setor']
                ],
                [],
                [
                    ['nome','ASC']
                ]
            );
        }

        $setores = array_column(json_decode(json_encode($setores), true), 'nome', 'id');
        return $setores;
    }
}
