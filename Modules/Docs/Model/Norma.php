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

    public $rules = [
        'descricao'          => 'required|string|min:5|max:100|unique:docs_norma,descricao',
        'orgaoRegulador'     => 'required|numeric',
        'cicloAuditoria'     => 'required|numeric',
    ];

    public function docsItemNorma()
    {
        return $this->hasMany('Modules\Docs\Model\ItemNorma', 'norma_id', 'id');
    }
}
