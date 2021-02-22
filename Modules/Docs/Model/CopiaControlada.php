<?php

namespace Modules\Docs\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CopiaControlada extends Model
{
    use SoftDeletes;

    protected $table = 'docs_copia_controlada';

    protected $fillable = [
        'id',
        'numero_copias',
        'revisao',
        'documento_id',
        'user_id',
        'setor'
    ];

    public $rules = [
        'documento_id'    => 'required|integer|exists:docs_documento,id',
        'user_id'         => 'required|integer|exists:core_users,id',
        'numero_copias'   => 'required|string',
        'revisao'         => 'required|string',
        'setor'           => 'required|string'
    ];

    /** O usuário 'responsável' pela substituição da cópia física */
    public function coreUsers()
    {
        return $this->belongsTo('Modules\Core\Model\User', 'user_id');
    }

    public function getResponsavelAttribute()
    {
        return $this->user->name;
    }

}
