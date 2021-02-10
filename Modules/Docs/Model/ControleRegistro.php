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
        'nivel_acesso_id',
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

    public $rules = [
        'codigo'          => 'required|string|unique:docs_controle_registros,codigo',
        'descricao'       => 'required|string|min:5|max:100',
        'responsavel'     => 'required|numeric',
        'meio'            => 'required|numeric',
        'armazenamento'   => 'required|numeric',
        'protecao'        => 'required|numeric',
        'recuperacao'     => 'required|numeric',
        'nivelAcesso'     => 'required|numeric',
        'retencaoLocal'   => 'required|numeric',
        'retencaoDeposito' => 'required|numeric',
        'disposicao'      => 'required|numeric'
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

    public function coreSetor()
    {
        return $this->hasOne('Modules\Core\Model\Setor', 'id', 'setor_id');
    }

    public function docsOcoesControleRegistroMeio()
    {
        return $this->hasOne('Modules\Docs\Model\OpcoesControleRegistros', 'id', 'meio_distribuicao_id');
    }

    public function docsOcoesControleRegistroArmazenamento()
    {
        return $this->hasOne('Modules\Docs\Model\OpcoesControleRegistros', 'id', 'local_armazenamento_id');
    }

    public function docsOcoesControleRegistroProtecao()
    {
        return $this->hasOne('Modules\Docs\Model\OpcoesControleRegistros', 'id', 'protecao_id');
    }

    public function docsOcoesControleRegistroRecuperacao()
    {
        return $this->hasOne('Modules\Docs\Model\OpcoesControleRegistros', 'id', 'recuperacao_id');
    }

}
