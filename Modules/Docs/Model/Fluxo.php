<?php

namespace Modules\Docs\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fluxo extends Model
{
    use SoftDeletes;

    protected $table = "docs_fluxo";

    protected $fillable = [
        'id',
        'nome',
        'descricao',
        'versao_fluxo',
        'grupo_id',
        'perfil_id',
        'ativo'
    ];
}
