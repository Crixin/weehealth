<?php

namespace App\Http\Controllers;

use App\Classes\Helper;
use App\Setup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SetupController extends Controller
{
    public function index()
    {
        return view('configuracoes.setup.index');
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

            $setup = Setup::find(1);
    
            if ($request->logo_login) {
                $mimeType = $request->file('logo_login')->getMimeType();
                $imageBase64 = base64_encode(file_get_contents($request->file('logo_login')->getRealPath()));
                $imageBase64 = 'data:' . $mimeType . ';base64,' . $imageBase64;
                
                $setup->logo_login = $imageBase64;
            }
    
            if ($request->logo_sistema) {
                $mimeType = $request->file('logo_sistema')->getMimeType();
                $imageBase64 = base64_encode(file_get_contents($request->file('logo_sistema')->getRealPath()));
                $imageBase64 = 'data:' . $mimeType . ';base64,' . $imageBase64;
                
                $setup->logo_sistema = $imageBase64;
            }
    
            $setup->save();
            Helper::setNotify('Informações alteradas com sucesso!', 'success|check-circle');
            return redirect()->back()->withInput();
        } catch (\Throwable $th) {
            Helper::setNotify("Falha ao atualizar as informações", 'danger|close-circle');
            return redirect()->back()->withInput();
        }
    }
}
