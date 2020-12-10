<?php

namespace Modules\Docs\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentoVinculado extends Model
{
    use SoftDeletes;

    protected $table = "docs_documento_vinculado";

    protected $fillable = [
        'id',
        'documento_id',
        'documento_vinculado_id'
    ];
}
