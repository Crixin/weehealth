<?php

namespace Modules\Docs\Model;

use Illuminate\Database\Eloquent\Model;

class GrupoPlano extends Model
{
    protected $table = "docs_grupo_plano";

    protected $fillable = [
        'id',
        'grupo_id',
        'norma_id'
    ];

}