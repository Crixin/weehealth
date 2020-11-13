<?php

namespace Modules\Docs\Model;

use Illuminate\Database\Eloquent\Model;

class UsuarioExtra extends Model
{
    
    protected $table = 'docs_usuario_extra';

    protected $fillable = [
        'id',
        'usuario_id',
        'documento_id'
    ];

}
