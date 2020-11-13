<?php

namespace Modules\Docs\Model;

use Illuminate\Database\Eloquent\Model;

class Anexo extends Model
{
    protected $table = "docs_anexo";

    protected $fillable = [
        'id',
        'nome',
        'hash',
        'extensao',
        'documento_id'
    ];

}
