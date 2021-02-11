<?php

namespace Modules\Docs\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HistoricoDocumento extends Model
{
    use SoftDeletes;

    protected $table = "docs_historico_documento";

    protected $fillable = [
        'descricao',
        'documento_id',
        'user_id'
    ];

    public $rules =  [
        'descricao'       => 'required|string|min:5|max:300',
        'documento_id'    => 'required|integer|exists:docs_documento,id',
        'user_id'         => 'required|integer|exists:core_users,id',
    ];

    public function docsDocumento()
    {
        return $this->hasOne('Modules\Docs\Model\Documento', 'id', 'documento_id');
    }

    public function coreUsers()
    {
        return $this->hasOne('Modules\Core\Model\User', 'id', 'user_id');
    }
}
