<?php

namespace Modules\Docs\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fluxo extends Model
{
    use SoftDeletes;

    protected $table = "docs_fluxo";

    protected $fillable = [
        'id',
        'nome',
        'descricao',
        'versao',
        'grupo_id',
        'perfil_id',
        'ativo'
    ];

    public function docsEtapaFluxo()
    {
        return $this->hasMany('Modules\Docs\Model\EtapaFluxo', 'fluxo_id', 'id')->orderBy('ordem', 'asc');
    }

    public function docsEtapaFluxoInversao()
    {
        return $this->hasMany('Modules\Docs\Model\EtapaFluxo', 'fluxo_id', 'id')->orderBy('ordem', 'desc')->limit(1);
    }
    
    public function coreGrupo()
    {
        return $this->hasOne('Modules\Core\Model\Grupo', 'id', 'grupo_id');
    }
}
