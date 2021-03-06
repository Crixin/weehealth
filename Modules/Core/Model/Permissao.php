<?php

namespace Modules\Core\Model;

use Illuminate\Database\Eloquent\Model;

class Permissao extends Model
{
    public $table = 'core_permissao';

    protected $fillable = [
        'id',
        'nome',
        'modulo'
    ];


    public function corePerfil()
    {
        return $this->belongsToMany('Modules\Core\Model\Perfil', 'Modules\Core\Model\Perfil_permissao');
    }
}
