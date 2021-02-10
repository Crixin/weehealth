<?php

namespace Modules\Docs\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UsuarioExtra extends Model
{
    use SoftDeletes;

    protected $table = 'docs_usuario_extra';

    protected $fillable = [
        'id',
        'user_id',
        'documento_id'
    ];

    public $rules = [
        'documento_id' => 'required|integer|exists:docs_documento,id',
        'user_id' => 'required|integer|exists:core_users,id'
    ];
}
