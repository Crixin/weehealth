<?php

namespace Modules\Docs\Model;

use Illuminate\Database\Eloquent\Model;

class OrientacaoItemNorma extends Model
{
    protected $table = "docs_orientacao_item_norma";

    protected $fillable = [
        'id',
        'descricao',
        'item_norma_id'
    ];

}