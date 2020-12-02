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
        'usuario_id',
        'documento_id'
    ];
}
