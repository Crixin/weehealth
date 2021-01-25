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
        'etapa_fluxo_id',
        'grupo_id'
    ];

    public function docsDocumento()
    {
        return $this->hasOne('Modules\Docs\Model\Documento', 'id', 'documento_id');
    }

    public function docsEtapa()
    {
        return $this->hasOne('Modules\Docs\Model\EtapaFluxo', 'id', 'etapa_fluxo_id');
    }

    public function coreUsers()
    {
        return $this->hasOne('Modules\Core\Model\User', 'id', 'user_id');
    }
}
