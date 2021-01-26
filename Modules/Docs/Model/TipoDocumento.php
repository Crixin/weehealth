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
        'periodo_vigencia',
        'ativo',
        'vinculo_obrigatorio',
        'permitir_download',
        'permitir_impressao',
        'periodo_aviso',
        'modelo_documento',
        'codigo_padrao',
        'vinculo_obrigatorio_outros_documento',
        'numero_padrao_id',
        'ultimo_documento',
        'extensao'
    ];

    public function docsFluxo()
    {
        return $this->hasOne('Modules\Docs\Model\Fluxo', 'id', 'fluxo_id');
    }
}
