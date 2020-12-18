<?php

namespace Modules\Docs\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Norma extends Model
{
    use SoftDeletes;

    protected $table = "docs_norma";

    protected $fillable = [
        'id',
        'descricao',
        'orgao_regulador_id',
        'ativo',
        'ciclo_auditoria_id',
        'data_acreditacao'
    ];

    public function docsItemNorma()
    {
        return $this->hasMany('Modules\docs\Model\ItemNorma', 'norma_id', 'id');
    }
}
