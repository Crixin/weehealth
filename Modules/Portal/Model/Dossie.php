<?php

namespace Modules\Portal\Model;

use Illuminate\Database\Eloquent\Model;

class Dossie extends Model
{
    public $table = 'portal_dossie';

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
        return $this->hasMany('Modules\Portal\Model\DossieEmpresaProcesso');
    }
}
