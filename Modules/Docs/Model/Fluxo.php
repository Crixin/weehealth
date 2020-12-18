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
        return $this->hasMany('Modules\docs\Model\EtapaFluxo', 'fluxo_id', 'id');
    }
}
