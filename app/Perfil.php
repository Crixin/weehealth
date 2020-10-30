<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Perfil extends Model
{
    public $table = 'perfil';

    protected $fillable = [
        'id',
        'nome'
    ];


    public function permissoes()
    {
        return $this->belongsToMany('App\Permissao');
    }


    public function users()
    {
        return $this->hasMany('App\User');
    }

}
