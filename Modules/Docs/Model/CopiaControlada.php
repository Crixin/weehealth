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
        'setor',
        'documento_id',
        'usuario_id'
    ];

    /** O usuário 'responsável' pela substituição da cópia física */
    public function coreUser()
    {
        return $this->belongsTo('Modules\Core\Model\User', 'usuario_id');
    }

    public function getResponsavelAttribute()
    {
        return $this->user->name;
    }

}
