<?php

namespace Modules\Docs\Model;

use Illuminate\Database\Eloquent\Model;

class AprovadorGrupo extends Model
{

    protected $table = "docs_aprovador_grupo";

    protected $fillable = [
        'id',
        'usuario_id',
        'grupo_id'
    ];

}
