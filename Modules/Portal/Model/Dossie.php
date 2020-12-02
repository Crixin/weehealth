<?php

namespace Modules\Portal\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dossie extends Model
{
    use SoftDeletes;

    public $table = 'portal_dossie';

    protected $fillable = [
        'id',
        'titulo',
        'caminho_documento',
        'destinatarios',
        'status',
        'validade'
    ];


    public function portalDossieEmpresaProcesso()
    {
        return $this->hasMany('Modules\Portal\Model\DossieEmpresaProcesso');
    }
}
