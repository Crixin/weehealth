<?php

namespace Modules\Docs\Model;

use Modules\Core\Model\User;
use Illuminate\Database\Eloquent\Model;

class DocumentoExterno extends Model
{
    protected $table = "docs_documento_externo";

    protected $fillable = [
        'id',
        'id_documento',
        'id_registro',
        'id_area',
        'validado',
        'responsavel_upload_id',
        'user_id',
        'setor_id',
        'empresa_id',
        'revisao',
        'validade',
    ];


    public function getAprovadorAttribute()
    {
        return User::find($this->user_id)->name;
    }

    public function getResponsavelUploadAttribute()
    {
        return User::find($this->responsavel_upload_id)->name;
    }


    public function coreEmpresa()
    {
        return $this->hasOne('Modules\Core\Model\Empresa', 'id', 'empresa_id');
    }


    public function docsSetor()
    {
        return $this->hasOne('Modules\Docs\Model\Setor', 'id', 'setor_id');
    }
}
