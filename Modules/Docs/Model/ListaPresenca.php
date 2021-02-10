<?php

namespace Modules\Docs\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ListaPresenca extends Model
{
    use SoftDeletes;

    protected $table = 'docs_lista_presenca';

    protected $fillable = [
        'id',
        'nome',
        'ged_documento_id',
        'data',
        'descricao',
        'documento_id',
        'destinatarios_email',
        'revisao_documento'
    ];

}
