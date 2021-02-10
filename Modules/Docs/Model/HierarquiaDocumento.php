<?php

namespace Modules\Docs\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HierarquiaDocumento extends Model
{
    use SoftDeletes;

    protected $table = "docs_hierarquia_documento";

    protected $fillable = [
        'id',
        'documento_id',
        'documento_pai_id'
    ];

    public $rules = [
        'documento_id' => 'required|integer|exists:docs_documento,id',
        'documento_pai_id' => 'required|integer|exists:docs_documento,id'
    ];
}
