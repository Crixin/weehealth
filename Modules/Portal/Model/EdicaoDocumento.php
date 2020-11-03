<?php

namespace Modules\Portal\Model;

use Illuminate\Database\Eloquent\Model;

class EdicaoDocumento extends Model
{
    public $table = 'edicao_documento';

    protected $fillable = [
        'id', 'user_id', 'documento_id', 'documento_nome'
    ];
}
