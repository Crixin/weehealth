<?php

namespace Modules\Docs\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentoPai extends Model
{
    use SoftDeletes;

    protected $table = "docs_documento_pai";

    protected $fillable = [
        'id',
        'documento_id',
        'documento_pai_id'
    ];
}
