<?php

namespace Modules\Docs\Model;

use Illuminate\Database\Eloquent\Model;

class ListaPresenca extends Model
{
    protected $table = 'docs_lista_presenca';

    protected $fillable = [
        'id',
        'nome',
        'extensao',
        'data',
        'descricao',
        'documento_id',
        'destinatarios_email',
        'revisao_documento'
    ];

}
