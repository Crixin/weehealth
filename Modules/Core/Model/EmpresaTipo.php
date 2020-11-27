<?php

namespace Modules\Core\Model;

use Illuminate\Database\Eloquent\Model;

class EmpresaTipo extends Model
{
    public $table = 'core_empresa_tipo';

    protected $fillable = [
        'id',
        'empresa_id',
        'tipo_id'
    ];
}
