<?php

namespace Modules\Docs\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Anexo extends Model
{
    use SoftDeletes;

    protected $table = "docs_anexo";

    protected $fillable = [
        'id',
        'nome',
        'hash',
        'extensao',
        'documento_id'
    ];

}
