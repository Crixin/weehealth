<?php

namespace Modules\Core\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmpresaTipo extends Model
{
    use SoftDeletes;
    public $table = 'core_empresa_tipo';

    protected $fillable = [
        'id',
        'empresa_id',
        'tipo_id'
    ];
}
