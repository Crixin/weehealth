<?php

namespace Modules\Docs\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentoFormulario extends Model
{
    use SoftDeletes;

    protected $table = "docs_documento_formulario";

    protected $fillable = [
        'id',
        'documento_id',
        'formulario_id'
    ];
}
