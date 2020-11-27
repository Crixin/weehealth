<?php

namespace Modules\Portal\Model;

use Illuminate\Database\Eloquent\Model;

class EmpresaProcessoGrupo extends Model
{
    public $table = "portal_empresa_processo_grupo";

    protected $fillable = [
        'id',
        'grupo_id',
        'empresa_processo_id',
        'filtros',
    ];


    public function coreGrupo()
    {
        return $this->belongsTo('Modules\Core\Model\Grupo', 'grupo_id');
    }

    public function portalEmpresaProcesso()
    {
        return $this->belongsTo('Modules\Portal\Model\EmpresaProcesso', 'empresa_processo_id');
    }
}
