<?php

namespace Modules\Docs\Model;

use Illuminate\Database\Eloquent\Model;

class TipoDocumentoPlano extends Model
{
    protected $table = "docs_tipo_documento_plano";

    protected $fillable = [
        'id',
        'plano_id',
        'tipo_documento_id'
    ];

}
