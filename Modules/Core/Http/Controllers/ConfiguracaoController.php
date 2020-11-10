<?php

namespace Modules\Core\Http\Controllers;

use App\Classes\Constants;
use Modules\Core\Model\{Parametro, User};

class ConfiguracaoController extends Controller
{
    public function indexParameters()
    {
        $params = Parametro::orderBy('identificador_parametro')->get();
        return view('core::configuracoes.index_parametros', compact('params'));
    }


    public function indexAdministrators()
    {
        $users = User::whereNotIn('id', Constants::$ARR_SUPER_ADMINISTRATORS_ID)->get();
        return view('core::configuracoes.index_administradores', compact('users'));
    }
}
