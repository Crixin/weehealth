<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permissao extends Model
{
    public $table = 'permissao';

    protected $fillable = [
        'id',
        'nome'
    ];


    public function perfil()
    {
        return $this->belongsToMany('App\Perfil');
    }
}
