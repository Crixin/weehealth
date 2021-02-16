<?php

namespace Modules\Portal\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Logs extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'id', 'idArea', 'idRegistro', 'idDocumento', 'acao', 'referencia', 'idUsuario', 'data', 'nomeProcesso', 'descricao', 'complemento', 'valor',
    ];

    protected $dates = [
        'data'
    ];
}
