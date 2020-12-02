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
        'extensao',
        'tipo_documento_id',
        'validade',
        'status',
        'observacao',
        'copia_controlada',
        'nivel_acesso',
        'finalizado',
        'grupo_treinamento_id',
        'elaborador_id',
        'grupo_divulgacao_id',
        'necessita_revisao',
        'usuario_solicitante_id',
        'revisao',
        'justificativa_cancelar_revisao',
        'obsoleto',
        'data_revisao',
        'validade_anterior',
        'data_revisao_anterior',
        'revisao_curta',
        'tipo'
    ];
}
