<?php

namespace Modules\Docs\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserEtapaDocumento extends Model
{
    use SoftDeletes;

    protected $table = "docs_user_etapa_documento";

    protected $fillable = [
        'user_id',
        'documento_id',
        'etapa_fluxo_id'
    ];
}
