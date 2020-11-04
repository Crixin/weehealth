<?php

namespace Modules\Portal\Model;

use Illuminate\Database\Eloquent\Model;

class Gerenciador extends Model
{
    
    public $table = 'gerenciador';

    protected $fillable = [
        'id', 'id_cliente', 'nome_cliente', 'obs'
    ];
}