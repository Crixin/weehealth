<?php

namespace Modules\Portal\Model;

use Illuminate\Database\Eloquent\Model;

class ConfiguracaoTarefa extends Model
{
    public $table = 'portal_configuracao_tarefa';

    protected $fillable = [
        'id', 'nome', 'tipo', 'caminho', 'ip', 'porta', 'usuario', 'senha'
    ];
}
