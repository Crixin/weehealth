<?php

namespace Modules\Docs\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CheckListItemNorma extends Model
{
    use SoftDeletes;

    protected $table = "docs_check_list_item_norma";

    protected $fillable = [
        'id',
        'descricao',
        'item_norma_id'
    ];

    public $rules = [
        'descricao'          => 'required|string|min:5|max:100',
        'item_norma_id'      => 'required|integer|exists:docs_item_norma,id',
    ];

    public function docsItemNorma()
    {
        return $this->hasOne('Modules\Docs\Model\ItemNorma', 'id', 'item_norma_id');
    }
}
