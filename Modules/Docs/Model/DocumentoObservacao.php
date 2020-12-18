<?php

namespace Modules\Docs\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentoObservacao extends Model
{
    use SoftDeletes;

    protected $table = "docs_documento_observacao";

    protected $fillable = [
        'id',
        'observacao',
        'documento_id',
        'user_id'
    ];
}
