<?php

namespace Modules\Docs\Model;

use Illuminate\Database\Eloquent\Model;

class TipoDocumento extends Model
{
    
    protected $table = 'docs_tipo_documento';

    protected $fillable = [
        'id',
        'nome',
        'descricao',
        'sigla',
        'docs_fluxo_id',
        'docs_tipo_documento_pai_id'
    ];
}
