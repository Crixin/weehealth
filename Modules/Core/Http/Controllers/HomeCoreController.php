<?php

namespace Modules\Core\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class HomeCoreController extends Controller
{

    public function index()
    {
        if (is_null(Auth::user())) {
            return redirect()->route('login');
        }
        //dd('home modulo core');
        return view('core::home.index');
    }
}
