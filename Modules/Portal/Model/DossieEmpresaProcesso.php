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


    public function portalDossie()
    {
        return $this->belongsTo('Modules\Portal\Model\Dossie', 'dossie_id');
    }


    public function portalEmpresaProcesso()
    {
        return $this->belongsTo('Modules\Portal\Model\EmpresaProcesso', 'empresa_processo_id');
    }
}
