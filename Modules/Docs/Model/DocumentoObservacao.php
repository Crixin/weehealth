<?php

namespace Modules\Docs\Model;

use Illuminate\Database\Eloquent\Model;

class DocumentoObservacao extends Model
{

    protected $table = "docs_documento_observacao";

    protected $fillable = [
        'id',
        'observacao',
        'nome_usuario_responsavel',
        'documento_id',
        'usuario_id'
    ];

}
