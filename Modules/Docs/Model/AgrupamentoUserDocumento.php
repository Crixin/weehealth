<?php

namespace Modules\Docs\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AgrupamentoUserDocumento extends Model
{
    use SoftDeletes;

    protected $table = "docs_agrupamento_user_documento";

    protected $fillable = [
        'id',
        'documento_id',
        'user_id',
        'tipo'
    ];

}
