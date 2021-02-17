<?php

namespace Modules\Docs\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

    public $rules = [
        'nome'                  => 'required|string|min:5|max:100|unique:docs_tipo_documento,nome',
        'descricao'             => 'required|string|min:5|max:200',
        'sigla'                 => 'required|string|min:1|max:5',
        'fluxo'                 => 'required|string|max:50',
        'periodoVigencia'       => 'required|numeric',
        'periodoAviso'          => 'required|numeric',
        'documentoModelo'       => 'sometimes|mimes:xlsx,xls,docx,doc',
        'codigoPadrao'          => 'required',
        'numeroPadrao'          => 'required',
        'ultimoDocumento'       => 'required|numeric|min:0'
    ];

    public function docsFluxo()
    {
        return $this->hasOne('Modules\Docs\Model\Fluxo', 'id', 'fluxo_id');
    }

    public function docsDocumento()
    {
        return $this->hasMany('Modules\Docs\Model\Documento', 'tipo_documento_id', 'id');
    }
}
