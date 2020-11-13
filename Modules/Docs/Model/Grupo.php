<?php

namespace Modules\Docs\Model;

use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    
    protected $table = "docs_grupo";

    protected $fillable = [
        'id',
        'documento_id',
        'usuario_id',
        'tipo'
    ];

}
