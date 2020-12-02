<?php

namespace Modules\Docs\Model;

use Illuminate\Database\Eloquent\Model;
use App\Classes\Constants;
use Illuminate\Database\Eloquent\SoftDeletes;

class ControleRegistro extends Model
{
    use SoftDeletes;

    protected $table = "docs_controle_registros";

    protected $fillable = [
        'id',
        'codigo',
        'titulo',
        'meio_distribuicao',
        'local_armazenamento',
        'protecao',
        'recuperacao',
        'nivel_acesso',
        'tempo_retencao_local',
        'tempo_retencao_deposito',
        'disposicao',
        'avulso',
        'documento_id',
        'setor_id',
        'local_armazenamento_id',
        'disposicao_id',
        'meio_distribuicao_id',
        'protecao_id',
        'recuperacao_id',
        'tempo_retencao_deposito_id',
        'tempo_retencao_local_id',
        'ativo'
    ];


    /**
     * O setor desse registro
     */
    public function docsSetor()
    {
        return $this->belongsTo('Modules\Docs\Model\Setor');
    }


    /**
     * O formulário desse registro
     */
    public function docsDocumento()
    {
        return $this->belongsTo('Modules\Docs\Model\Documento');
    }

    /**
     * Mutator - Título do formulário
     */
    public function getTituloAttribute($value)
    {
        return explode(Constants::$SUFIXO_REVISAO_NOS_TITULO_DOCUMENTOS, $value)[0];
    }

}
