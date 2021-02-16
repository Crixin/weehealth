<?php

namespace Modules\Core\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Parametro extends Model
{
    use SoftDeletes;
    public $table = 'core_parametro';

    protected $fillable = [
        'id',
        'identificador_parametro',
        'descricao',
        'valor_padrao',
        'valor_usuario',
        'ativo'
    ];
}
