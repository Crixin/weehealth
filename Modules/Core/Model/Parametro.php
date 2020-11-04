<?php

namespace Modules\Core\Model;

use Illuminate\Database\Eloquent\Model;

class Parametro extends Model
{
    
    public $table = 'core_parametro';

    protected $fillable = [
        'id', 'identificador_parametro', 'descricao', 'valor_padrao', 'valor_usuario', 'ativo'
    ];
}
