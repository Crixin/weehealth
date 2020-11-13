<?php

namespace Modules\Docs\Model;

use Illuminate\Database\Eloquent\Model;

class Historico extends Model
{

    protected $table = "docs_historico";
    
    protected $fillable = [
        'id',
        'descricao',
        'id_usuario_responsavel',
        'nome_usuario_responsavel',
        'documento_id'
    ];

}
