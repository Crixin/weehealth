<?php

namespace Modules\Docs\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plano extends Model
{
    use SoftDeletes;

    protected $fillable =
    [
        'id',
        'nome',
        'ativo',
        'grupo_id'
    ];

    protected $table = "docs_plano";
}
