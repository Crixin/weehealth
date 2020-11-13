<?php

namespace Modules\Docs\Model;

use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
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
        'setor_id',
        'elaborador_id',
        'aprovador_id',
        'necessita_revisao',
        'id_usuario_solicitante',
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
