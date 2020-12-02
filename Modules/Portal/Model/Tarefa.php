<?php

namespace Modules\Portal\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tarefa extends Model
{
    use SoftDeletes;

    public $table = 'portal_tarefa';

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

    public function portalConfiguracaoTarefa()
    {
        return $this->hasOne('Modules\Portal\Model\ConfiguracaoTarefa', 'id', 'configuracao_id');
    }
}
