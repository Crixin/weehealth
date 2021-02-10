<?php

namespace Modules\Docs\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoDocumentoPlano extends Model
{
    use SoftDeletes;

    protected $table = "docs_tipo_documento_plano";

    protected $fillable = [
        'id',
        'plano_id',
        'tipo_documento_id'
    ];

    public $rules = [
        'documento_id' => 'required|integer|exists:docs_documento,id',
        'plano_id' => 'required|integer|exists:docs_plano,id'
    ];
}
