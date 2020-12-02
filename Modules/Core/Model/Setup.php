<?php

namespace Modules\Core\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Setup extends Model
{
    use SoftDeletes;
    public $table = 'core_setup';

    protected $fillable = [
        'id',
        'logo_login',
        'logo_sistema'
    ];
}
