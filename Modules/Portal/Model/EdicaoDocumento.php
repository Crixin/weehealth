<?php

namespace Modules\Portal\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EdicaoDocumento extends Model
{
    use SoftDeletes;

    public $table = 'portal_edicao_documento';

    protected $fillable = [
        'id', 'user_id', 'documento_id', 'documento_nome'
    ];
}
