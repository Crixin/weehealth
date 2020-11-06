<?php

namespace Modules\Core\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class HomeCoreController extends Controller
{
    public function index()
    {
        //dd('home modulo core');
        $a = Auth::user()
        ->coreEnterprises()
        ->select('nome')
        ->orderBy('nome')
        ->toSql();
        dd($a);
        return view('core::home.index');
    }
}
