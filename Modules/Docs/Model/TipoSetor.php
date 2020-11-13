<?php

namespace Modules\Docs\Model;

use Illuminate\Database\Eloquent\Model;

class TipoSetor extends Model
{
    
    protected $table = "docs_tipo_setor";

    protected $fillable = [
        'id',
        'nome'
    ];

}
