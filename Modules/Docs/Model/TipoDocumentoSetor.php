<?php

namespace Modules\Docs\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoDocumentoSetor extends Model
{
    use SoftDeletes;

    protected $table = "docs_tipo_documento_setor";

    protected $fillable = [
        'id',
        'setor_id',
        'tipo_documento_id',
        'ultimo_documento'
    ];

    public $rules = [
        'tipo_documento_id' => 'required|integer|exists:docs_tipo_documento,id',
        'setor_id' => 'required|integer|exists:core_setor,id'
    ];

    public function coreSetor()
    {
        return $this->hasOne('Modules\Core\Model\Setor', 'id', 'setor_id');
    }
}
