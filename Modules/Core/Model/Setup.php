<?php

namespace Modules\Core\Model;

use Illuminate\Database\Eloquent\Model;

class Setup extends Model
{
    public $table = 'core_setup';

    protected $fillable = [
        'id',
        'logo_login',
        'logo_sistema'
    ];
}
