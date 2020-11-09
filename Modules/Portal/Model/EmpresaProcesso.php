<?php

namespace Modules\Portal\Model;

use Illuminate\Database\Eloquent\Model;

class EmpresaProcesso extends Model
{
    
    public $table = 'portal_empresa_processo';

    protected $fillable = [
        'id', 'indice_filtro_utilizado', 'id_area_ged', 'empresa_id', 'processo_id', 'todos_filtros_pesquisaveis'
    ];


    public function portalProcesso()
    {
        return $this->hasOne('Modules\Portal\Model\Processo', 'id', 'processo_id');
    }


    public function coreEmpresa()
    {
        return $this->hasOne('Modules\Core\Model\Empresa', 'id', 'empresa_id');
    }


    public function portalEmpresaProcessoGrupo()
    {
        return $this->hasMany('Modules\Portal\Model\EmpresaProcessoGrupo');
    }
}
