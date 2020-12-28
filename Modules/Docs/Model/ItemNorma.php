<?php

namespace Modules\Docs\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemNorma extends Model
{
    use SoftDeletes;

    protected $table = "docs_item_norma";

    protected $fillable = [
        'id',
        'descricao',
        'norma_id',
        'numero'
    ];

    public function docsNorma()
    {
        return $this->hasOne('Modules\Docs\Model\Norma', 'id', 'norma_id');
    }

    public function docsCheckListItemNorma()
    {
        return $this->hasMany('Modules\Docs\Model\CheckListItemNorma', 'item_norma_id', 'id');
    }
}
