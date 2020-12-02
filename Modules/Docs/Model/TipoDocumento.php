<?php

namespace Modules\Docs\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoDocumento extends Model
{
    use SoftDeletes;

    protected $table = 'docs_tipo_documento';

    protected $fillable = [
        'id',
        'nome',
        'descricao',
        'sigla',
        'fluxo_id',
        'tipo_documento_pai_id',
        'periodo_vigencia_id',
        'ativo',
        'vinculo_obrigatorio',
        'permitir_download',
        'permitir_impressao',
        'periodo_aviso_id',
        'documento_modelo',
        'codigo_padrao'
    ];
}
