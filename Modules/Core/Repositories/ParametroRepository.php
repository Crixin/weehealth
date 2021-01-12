<?php

namespace Modules\Core\Repositories;

use Modules\Core\Repositories\BaseRepository;
use Modules\Core\Model\Parametro;

class ParametroRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new Parametro();
    }

    public function getParametro($key)
    {
        $consulta = $this->findOneBy(
            [
                ['identificador_parametro', '=', $key]
            ]
        );
        return $consulta->valor_usuario ? $consulta->valor_usuario : $consulta->valor_padrao;
    }
}
