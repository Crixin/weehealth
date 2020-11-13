<?php

namespace Modules\Docs\Model;

use Illuminate\Database\Eloquent\Model;

class RegistroImpressoes extends Model
{
    protected $table = "docs_registro_impressoes";

    // Não foi colocado 'mensagem' porque qualquer mudança posterior demandará atualização dessa mensagem, entre outras. 
    // Então, basicamente, se dejarmos criar uma mensagem como "O usuário {x}, às {data/hora}, acessou a opção de impressão do documento {y}", isso é possível de ser feito em tempo de execução, sem problema algum.
    protected $fillable = [
        'id',
        'status',
        'obs',
        'documento_id',
        'user_id'
    ];

}
