<?php

namespace Modules\Docs\Model;

use Illuminate\Database\Eloquent\Model;

class ItemNorma extends Model
{
    protected $table = "docs_item_norma";

    protected $fillable = [
        'id',
        'descricao',
        'norma_id'
    ];

}