<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tarefa extends Model
{
    public $table = 'tarefa';

    protected $fillable = [
        'id',
        'configuracao_id',
        'pasta',
        'frequencia',
        'identificador',
        'area',
        'tipo_indexacao',
        'indices',
        'pasta_rejeitados',
        'hora',
        'status'
    ];

    public function configuracaoTarefa()
    {
        return $this->hasOne('\App\ConfiguracaoTarefa', 'id', 'configuracao_id');
    }
}
