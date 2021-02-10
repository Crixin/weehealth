<?php

namespace Modules\Docs\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GrupoPlano extends Model
{
    use SoftDeletes;

    protected $table = "docs_grupo_plano";

    protected $fillable = [
        'id',
        'grupo_id',
        'norma_id'
    ];

    public $rules = [
        'grupo_id' => 'required|integer|exists:core_grupo,id',
        'norma_id' => 'required|integer|exists:docs_norma,id'
    ];
}
