<?php

namespace Modules\Portal\Model;

use Illuminate\Database\Eloquent\Model;

class DossieEmpresaProcesso extends Model
{
    public $table = 'portal_dossie_empresa_processo';

    protected $fillable = [
        'id',
        'dossie_id',
        'empresa_processo_id'
    ];


    public function dossie()
    {
        return $this->belongsTo('Modules\Portal\Model\Dossie');
    }


    public function empresaProcesso()
    {
        return $this->belongsTo('Modules\Portal\Model\EmpresaProcesso');
    }
}
