<?php

namespace Modules\Core\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notificacao extends Model
{
    use SoftDeletes;

    public $table = 'core_notificacao';

    protected $fillable = [
        'id',
        'nome',
        'tipo_id',
        'titulo_email',
        'corpo_email',
        'tipo_envio_notificacao_id',
        'documento_anexo',
        'tempo_delay_envio',
        'numero_tentativas_envio'
    ];

    public $rules = [
        'nome'      => 'required|string|min:5|unique:core_notificacao,nome',
        'tipoEnvio' => 'required',
        'titulo'    => 'required',
        'corpo'     => 'required',
        'tipoNotificacao' => 'required',
        'delay'      => 'min:0',
        'tentativas' => 'min:0'
    ];
}
