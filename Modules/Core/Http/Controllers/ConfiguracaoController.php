<?php

namespace Modules\Core\Http\Controllers;

use App\Classes\Constants;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Modules\Core\Model\{Parametro, User};
use Modules\Core\Repositories\ParametroRepository;
use App\Classes\Helper;
use Illuminate\Support\Facades\Validator;

class ConfiguracaoController extends Controller
{

    protected $parametroRepository;

    public function __construct(ParametroRepository $parametroRepository)
    {
        $this->parametroRepository = $parametroRepository;
    }

    public function index()
    {
        $params = Parametro::orderBy('identificador_parametro')->get();
        return view('core::configuracoes.parametro.index', compact('params'));
    }

    public function edit($id)
    {
        $parametro = $this->parametroRepository->find($id);

        return view('core::configuracoes.parametro.update', compact('parametro'));
    }

    public function update(Request $request)
    {
        $id = $request->get('idParametro');
        $this->validador($request);
        $update = $this->montaRequest($request);
        try {
            DB::transaction(function () use ($update, $id) {
                $grupo = $this->parametroRepository->update($update, $id);
            });
            Helper::setNotify('Parâmetro alterado com sucesso!', 'success|check-circle');
            return redirect()->route('core.configuracao.parametros');
        } catch (\Throwable $th) {
            Helper::setNotify('Um erro ocorreu ao alterar o parametro.', 'danger|close-circle');
            return redirect()->back()->withInput();
        }
    }

    public function validador(Request $request)
    {
        $validator = Validator::make
        (
            $request->all(),
            [
                'descricao'    => 'required|string',
                'valorPadrao'  => 'required|string',
                'valorUsuario' => 'required|string'
            ]
        );

        if ($validator->fails()) {
            Helper::setNotify($validator->messages()->first(), 'danger|close-circle');
            return redirect()->back()->withInput();
        }

        return true;
    }

    public function montaRequest(Request $request)
    {
        return [
            "descricao"          => $request->descricao,
            "valor_usuario"      => $request->valorUsuario
        ];
    }





    public function indexAdministrators()
    {
        $users = User::whereNotIn('id', Constants::$ARR_SUPER_ADMINISTRATORS_ID)->get();
        return view('core::configuracoes.administrador.index', compact('users'));
    }
}
