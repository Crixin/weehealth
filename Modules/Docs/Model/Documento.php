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
        'justificativa_rejeicao_etapa',
        'justificativa_cancelar_etapa',
        'obsoleto',
        'classificacao_id',
        'ged_documento_id',
        'setor_id'
    ];


    public $rules = [
        'tituloDocumento'    => 'required|string|min:5|max:100',
        'setor'              => 'required|numeric',
        'tipoDocumento'      => 'required|numeric',
        'nivelAcesso'        => 'required|numeric',
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
}
