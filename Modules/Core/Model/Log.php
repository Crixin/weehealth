<?php

namespace Modules\Core\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Log extends Model
{
    public $table = 'core_log';

    protected $fillable = [
        'id',
        'usuario',
        'tabela',
        'coluna',
        'chave',
        'operacao',
        'valor_velho',
        'valor_novo',
        'modulo',
        'obs'
    ];

}