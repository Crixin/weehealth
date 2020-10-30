<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConfiguracaoTarefa extends Model
{
    public $table = 'configuracao_tarefa';

    protected $fillable = [
        'id', 'nome', 'tipo', 'caminho', 'ip', 'porta', 'usuario', 'senha'
    ];
}
