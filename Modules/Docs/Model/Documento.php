<?php

namespace Modules\Docs\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\Repositories\ParametroRepository;

class Documento extends Model
{
    use SoftDeletes;

    protected $table = 'docs_documento';

    protected $fillable = [
        'id',
        'nome',
        'codigo',
        'tipo_documento_id',
        'validade',
        'copia_controlada',
        'nivel_acesso_id',
        'elaborador_id',
        'revisao',
        'obsoleto',
        'classificacao_id',
        'ged_registro_id',
        'setor_id',
        'extensao',
        'em_revisao',
        'bpmn_id'
    ];


    public $rules = [
        'tituloDocumento'    => 'required|string|min:5|max:100|unique:docs_documento,nome',
        'setor'              => 'required|numeric',
        'tipoDocumento'      => 'required|numeric',
        'nivelAcesso'        => 'required|numeric',
        'grupoTreinamentoDoc' => 'required|array|min:1',
        'grupoTreinamentoDoc.*' => 'required|string|distinct|min:1',
        'grupoDivulgacaoDoc' => 'required|array|min:1',
        'grupoDivulgacaoDoc.*' => 'required|string|distinct|min:1',

    ];

    public function docsNivelAcesso($id)
    {
        $parametro = new ParametroRepository();
        $busca = $parametro->findOneBy(
            [
                ['identificador_parametro', '=', 'NIVEL_ACESSO']
            ]
        );
        $nivel = json_decode($busca->valor_padrao);
        return $nivel->$id;
    }

    public function docsTipoDocumento()
    {
        return $this->hasOne('Modules\Docs\Model\TipoDocumento', 'id', 'tipo_documento_id');
    }

    public function docsWorkFlow()
    {
        return $this->hasMany('Modules\Docs\Model\Workflow', 'documento_id', 'id')->orderBy('id', 'DESC');
    }

    public function coreElaborador()
    {
        return $this->hasOne('Modules\Core\Model\User', 'id', 'elaborador_id');
    }

    public function coreSetor()
    {
        return $this->hasOne('Modules\Core\Model\Setor', 'id', 'setor_id');
    }

    public function docsUserEtapaDocumento()
    {
        return $this->hasMany('Modules\Docs\Model\UserEtapaDocumento', 'documento_id', 'id');
    }

    public function docsAgrupamentoUserDocumento()
    {
        return $this->hasMany('Modules\Docs\Model\AgrupamentoUserDocumento', 'documento_id', 'id');
    }

    public function docsEtapaFluxoDocumento()
    {
        return $this->hasManyThrough(
            'Modules\Docs\Model\EtapaFluxo',
            'Modules\Docs\Model\Workflow',
            'documento_id',
            'id',
            'id',
            'etapa_fluxo_id'
        );
    }
}
