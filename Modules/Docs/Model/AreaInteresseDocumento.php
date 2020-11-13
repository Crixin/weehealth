<?php

namespace Modules\Docs\Model;

use Illuminate\Database\Eloquent\Model;

class AreaInteresseDocumento extends Model
{
    protected $table = "docs_area_interesse_documento";

    protected $fillable = [
        'id',
        'documento_id',
        'usuario_id'
    ];

}
