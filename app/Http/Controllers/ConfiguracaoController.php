<?php

namespace App\Http\Controllers;

use App\Classes\Constants;
use App\{Parametro, User};
use Illuminate\Http\Request;

use Bugsnag\BugsnagLaravel\Facades\Bugsnag;
use RuntimeException;

class ConfiguracaoController extends Controller
{
    
    public function indexParameters() {
        $params = Parametro::orderBy('identificador_parametro')->get();
        return view('configuracoes.index_parametros', compact('params'));
    }


    public function indexAdministrators() {
        $users = User::whereNotIn('id', Constants::$ARR_SUPER_ADMINISTRATORS_ID)->get();
        return view('configuracoes.index_administradores', compact('users'));
    }

}
