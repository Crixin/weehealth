<?php

namespace Modules\Docs\Model;

use Illuminate\Database\Eloquent\Model;

class AgrupamentoUserDocumento extends Model
{

    protected $table = "docs_agrupamento_user_documento";

    protected $fillable = [
        'id',
        'documento_id',
        'usuario_id',
        'tipo'
    ];

}
