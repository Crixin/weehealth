<?php

namespace Modules\Docs\Model;

use Modules\Core\Model\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentoExterno extends Model
{
    use SoftDeletes;

    protected $table = "docs_documento_externo";

    protected $fillable = [
        'id',
        'ged_documento_id',
        'ged_registro_id',
        'ged_area_id',
        'validado',
        'user_responsavel_upload_id',
        'user_id',
        'setor_id',
        'empresa_id',
        'revisao',
        'validade',
    ];

    public $rules = [
        'setor'           => 'required|numeric',
        'fornecedor'      => 'required|numeric',
        'versao'          => 'required|numeric',
        'validade'        => 'required|date'
    ];


    public function getAprovadorAttribute()
    {
        return User::find($this->user_id)->name;
    }

    public function getResponsavelUploadAttribute()
    {
        return User::find($this->user_responsavel_upload_id)->name;
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
