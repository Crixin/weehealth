<?php

namespace Modules\Docs\Model;

use Illuminate\Database\Eloquent\Model;

class Plano extends Model
{
    protected $fillable =
    [
        'id',
        'nome',
        'ativo',
        'grupo_id'
    ];

    protected $table = "docs_plano";
}
