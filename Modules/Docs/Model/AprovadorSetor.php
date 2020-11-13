<?php

namespace Modules\Docs\Model;

use Illuminate\Database\Eloquent\Model;

class AprovadorSetor extends Model
{

    protected $table = "docs_aprovador_setor";

    protected $fillable = [
        'id',
        'usuario_id',
        'setor_id'
    ];

}
