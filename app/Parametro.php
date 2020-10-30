<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Parametro extends Model
{
    
    public $table = 'parametro';

    protected $fillable = [
        'id', 'identificador_parametro', 'descricao', 'valor_padrao', 'valor_usuario', 'ativo'
    ];

}
