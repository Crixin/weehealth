<?php

namespace Modules\Docs\Model;

use Illuminate\Database\Eloquent\Model;

class Norma extends Model
{
    protected $table = "docs_norma";

    protected $fillable = [
        'id',
        'descricao',
        'orgao_regulador_id',
        'ativo',
        'ciclo_auditoria_id',
        'data_acreditacao'
    ];

}
