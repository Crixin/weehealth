<?php

namespace Modules\Docs\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VinculoDocumento extends Model
{
    use SoftDeletes;

    protected $table = "docs_vinculo_documento";

    protected $fillable = [
        'id',
        'documento_id',
        'documento_vinculado_id'
    ];
}
