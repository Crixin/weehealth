<?php

namespace Modules\Docs\Model;

use Illuminate\Database\Eloquent\Model;

class Fluxo extends Model
{
    protected $table = "docs_fluxo";

    protected $fillable = [
        'id',
        'nome',
        'descricao',
        'versao_fluxo',
        'grupo_id',
        'perfil_id'
    ];

}
