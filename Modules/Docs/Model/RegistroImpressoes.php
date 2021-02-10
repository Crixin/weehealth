<?php

namespace Modules\Docs\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RegistroImpressoes extends Model
{
    use SoftDeletes;

    protected $table = "docs_registro_impressoes";

    // Não foi colocado 'mensagem' porque qualquer mudança posterior demandará atualização dessa mensagem, entre outras. 
    // Então, basicamente, se dejarmos criar uma mensagem como "O usuário {x}, às {data/hora}, acessou a opção de impressão do documento {y}", isso é possível de ser feito em tempo de execução, sem problema algum.
    protected $fillable = [
        'id',
        'documento_id',
        'user_id'
    ];

    public $rules = [
        'documento_id' => 'required|integer|exists:docs_documento,id',
        'user_id' => 'required|integer|exists:core_users,id'
    ];
}
