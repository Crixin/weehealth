<?php

namespace Modules\Core\Model;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    public $table = 'core_users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'username', 'email', 'utilizar_permissoes_nivel_usuario', 'password', 'administrador', 'foto', 'perfil_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    /**
     * Os grupos que o usuário pertence.
     */
    public function coreGroups()
    {
        return $this->belongsToMany('Modules\Portal\Model\Grupo');
    }


    /**
     * As empresas que o usuário pertence.
     */
    public function coreEnterprises()
    {
        return $this->belongsToMany('Modules\Core\Model\Empresa')->withPivot(
            'permissao_download',
            'permissao_visualizar',
            'permissao_impressao',
            'permissao_aprovar_doc',
            'permissao_excluir_doc',
            'permissao_upload_doc',
            'permissao_receber_email',
            'empresa_id',
            'user_id'
        );
    }


    /**
     * Este método dá um "apelido" para a coluna 'utilizar_permissoes_nivel_usuario' e, a partir disso, esse valor pode ser usado em qualquer lugar como 'permissao_nivel_usuario'
     *
     * https://laravel.com/docs/5.5/eloquent-mutators#accessors-and-mutators
     */
    public function getPermissaoNivelUsuarioAttribute()
    {
        return $this->attributes['utilizar_permissoes_nivel_usuario'];
    }


    public function corePerfil()
    {
        return $this->belongsTo('Modules\Core\Model\Perfil');
    }


    public function portalDashboards()
    {
        return $this->hasMany('Modules\Portal\Model\UserDashboard');
    }
}
