<?php

namespace Modules\Docs\Model;

use Illuminate\Database\Eloquent\Model;

class DocumentoFormulario extends Model
{
    protected $table = "docs_documento_formulario";

    protected $fillable = [
        'id',
        'documento_id',
        'formulario_id'
    ];

}
