<?php

namespace Modules\Core\Repositories;

use Illuminate\Support\Facades\Auth;
use Modules\Core\Model\Grupo;
use Modules\Core\Repositories\BaseRepository;

class GrupoRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new Grupo();
    }


    public function getGrupoUsuario($ID_SETOR_QUALIDADE)
    {
        $idUserGrupo = Auth::user()->setor_id;
        if ($idUserGrupo != $ID_SETOR_QUALIDADE) {
            $grupos = $this->find($idUserGrupo);
        } else {
            $grupos = $this->findBy(
                [
                    ['nome', '!=', 'Sem Setor']
                ],
                [],
                [
                    ['nome','ASC']
                ]
            );
        }
        $grupos = array_column($grupos, 'nome', 'id');

        return $grupos;
    }
}
