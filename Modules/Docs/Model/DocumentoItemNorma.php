<?php

namespace Modules\Docs\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentoItemNorma extends Model
{
    use SoftDeletes;

    protected $table = "docs_documento_item_norma";

    protected $fillable = [
        'id',
        'documento_id',
        'item_norma_id'
    ];

    public $rules = [
        'documento_id' => 'required|integer|exists:docs_documento,id',
        'item_norma_id' => 'required|integer|exists:docs_item_norma,id'
    ];
}
