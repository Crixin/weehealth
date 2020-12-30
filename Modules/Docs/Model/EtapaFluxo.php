<?php

namespace Modules\Docs\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\Repositories\ParametroRepository;

class EtapaFluxo extends Model
{
    use SoftDeletes;

    protected $table = "docs_etapa_fluxo";

    protected $fillable = [
        'id',
        'nome',
        'descricao',
        'perfil_id',
        'fluxo_id',
        'versao_fluxo',
        'status_id',
        'ordem',
        'enviar_notificacao',
        'notificacao_id',
        'permitir_anexo',
        'comportamento_criacao',
        'comportamento_edicao',
        'comportamento_aprovacao',
        'comportamento_visualizacao',
        'comportamento_divulgacao',
        'comportamento_treinamento',
        'tipo_aprovacao_id',
        'obrigatorio',
        'etapa_rejeicao_id',
        'exigir_lista_presenca'
    ];

    public function docsFluxo()
    {
        return $this->hasOne('Modules\Docs\Model\Fluxo', 'id', 'fluxo_id');
    }

    public function docsStatus($id)
    {
        $parametro = new ParametroRepository();
        $busca = $parametro->findOneBy(
            [
                ['identificador_parametro', '=', 'STATUS_ETAPA_FLUXO']
            ]
        );
        $status = json_decode($busca->valor_padrao);
        return $status->$id;
    }
}
