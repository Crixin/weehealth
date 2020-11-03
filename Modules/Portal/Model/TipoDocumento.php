<?php

namespace Modules\Portal\Model;

use Illuminate\Database\Eloquent\Model;

class TipoDocumento extends Model
{
    
    public $table = 'tipo_documento';

    protected $fillable = [
        'id', 'nome'
    ];
}
