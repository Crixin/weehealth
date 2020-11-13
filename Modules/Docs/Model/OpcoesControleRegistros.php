<?php

namespace Modules\Docs\Model;

use Illuminate\Database\Eloquent\Model;

class OpcoesControleRegistros extends Model
{
    
    protected $table = "docs_opcoes_controle_registros";

    protected $fillable = [
        'id',
        'campo',
        'descricao',
        'ativo'
    ];

}
