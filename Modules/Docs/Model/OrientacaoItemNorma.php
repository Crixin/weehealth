<?php

namespace Modules\Docs\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrientacaoItemNorma extends Model
{
    use SoftDeletes;

    protected $table = "docs_orientacao_item_norma";

    protected $fillable = [
        'id',
        'descricao',
        'item_norma_id'
    ];
}
