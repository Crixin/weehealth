<?php

namespace Modules\Docs\Model;

use Illuminate\Database\Eloquent\Model;

class EtapaFluxo extends Model
{
    protected $table = "docs_etapa_fluxo";

    protected $fillable = [
        'id',
        'nome',
        'descricao',
        'perfil_id'
    ];

}
