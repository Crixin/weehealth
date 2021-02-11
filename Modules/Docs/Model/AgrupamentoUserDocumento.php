<?php

namespace Modules\Docs\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AgrupamentoUserDocumento extends Model
{
    use SoftDeletes;

    protected $table = "docs_agrupamento_user_documento";

    protected $fillable = [
        'id',
        'documento_id',
        'user_id',
        'tipo',
        'grupo_id'
    ];

    public $rules = [
        'grupo_id'        => 'required|integer|exists:core_grupo,id',
        'user_id'         => 'required|integer|exists:core_users,id',
        'documento_id'    => 'required|integer|exists:docs_documento,id',
        'tipo'            => 'requided|string'
    ];
}
