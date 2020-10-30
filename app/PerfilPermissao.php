<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PerfilPermissao extends Model
{
    public $table = 'perfil_permissao';

    protected $fillable = [
        'id',
        'perfil_id',
        'permissao_id'
    ];
}
