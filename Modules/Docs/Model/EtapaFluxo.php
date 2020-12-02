<?php

namespace Modules\Docs\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EtapaFluxo extends Model
{
    use SoftDeletes;

    protected $table = "docs_etapa_fluxo";

    protected $fillable = [
        'id',
        'nome',
        'descricao',
        'perfil_id'
    ];
}
