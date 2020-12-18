<?php

namespace Modules\Docs\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Documento extends Model
{
    use SoftDeletes;

    protected $table = 'docs_documento';

    protected $fillable = [
        'id',
        'nome',
        'codigo',
        'tipo_documento_id',
        'validade',
        'copia_controlada',
        'nivel_acesso_id',
        'elaborador_id',
        'revisao',
        'justificativa_rejeicao_etapa',
        'justificativa_cancelar_etapa',
        'obsoleto',
        'classificacao_id',
        'ged_documento_id'
    ];
}
