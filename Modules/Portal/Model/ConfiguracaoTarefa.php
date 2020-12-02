<?php

namespace Modules\Portal\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConfiguracaoTarefa extends Model
{
    use SoftDeletes;

    public $table = 'portal_configuracao_tarefa';

    protected $fillable = [
        'id', 'nome', 'tipo', 'caminho', 'ip', 'porta', 'usuario', 'senha'
    ];
}
