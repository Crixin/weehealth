<?php

namespace Modules\Core\Http\Controllers;

use App\Classes\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Core\Repositories\SetupRepository;

class SetupController extends Controller
{
    protected $setupRepository;

    public function __construct()
    {
        $this->setupRepository = new SetupRepository();
    }

    public function index()
    {
        return view('core::configuracoes.setup.index');
    }

    public function update(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'logo_login' => 'image|mimes:jpeg,png,jpg',
                'logo_sistema' => 'image|mimes:jpeg,png,jpg',
            ]);

            if ($validator->fails()) {
                Helper::setNotify($validator->messages()->first(), 'danger|close-circle');
                return redirect()->back()->withInput();
            }

            $setup = $this->setupRepository->find(1);

            $logoSistema = $setup->logo_sistema;
            $logoLogin   = $setup->logo_login;
            if ($request->logo_login) {
                $mimeType = $request->file('logo_login')->getMimeType();
                $imageBase64 = base64_encode(file_get_contents($request->file('logo_login')->getRealPath()));
                $imageBase64 = 'data:' . $mimeType . ';base64,' . $imageBase64;
                $logoSistema = $imageBase64;
            }

            if ($request->logo_sistema) {
                $mimeType = $request->file('logo_sistema')->getMimeType();
                $imageBase64 = base64_encode(file_get_contents($request->file('logo_sistema')->getRealPath()));
                $imageBase64 = 'data:' . $mimeType . ';base64,' . $imageBase64;
                $logoLogin = $imageBase64;
            }
            $this->setupRepository->update(['logo_login' => $logoLogin, 'logo_sistema' => $logoSistema], 1);
            Helper::setNotify('Informações alteradas com sucesso!', 'success|check-circle');
            return redirect()->back()->withInput();
        } catch (\Throwable $th) {
            Helper::setNotify("Falha ao atualizar as informações", 'danger|close-circle');
            return redirect()->back()->withInput();
        }
    }
}
