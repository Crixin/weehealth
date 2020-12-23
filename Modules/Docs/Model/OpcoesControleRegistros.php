<?php

namespace Modules\Docs\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OpcoesControleRegistros extends Model
{
    use SoftDeletes;

    protected $table = "docs_opcoes_controle_registros";

    protected $fillable = [
        'id',
        'campo',
        'descricao',
        'ativo',
        'campo_id'
    ];
}
