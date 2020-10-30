<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dossie extends Model
{
    public $table = 'dossie';

    protected $fillable = [
        'id',
        'titulo',
        'caminho_documento',
        'destinatarios',
        'status',
        'validade'
    ];


    public function dossieEmpresaProcesso()
    {
        return $this->hasMany('App\DossieEmpresaProcesso');
    }
}
