<?php

namespace Modules\Docs\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Historico extends Model
{
    use SoftDeletes;

    protected $table = "docs_historico";

    protected $fillable = [
        'id',
        'descricao',
        'usuario_id',
        'documento_id'
    ];
}
