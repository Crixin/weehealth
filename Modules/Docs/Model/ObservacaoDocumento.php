<?php

namespace Modules\Docs\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ObservacaoDocumento extends Model
{
    use SoftDeletes;

    protected $table = "docs_observacao_documento";

    protected $fillable = [
        'id',
        'observacao',
        'documento_id',
        'user_id'
    ];


    public function coreUsers()
    {
        return $this->hasOne('Modules\Core\Model\User', 'id', 'user_id');
    }
}
