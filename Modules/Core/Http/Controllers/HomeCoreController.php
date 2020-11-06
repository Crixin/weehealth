<?php

namespace Modules\Core\Http\Controllers;

class HomeCoreController extends Controller
{
    public function index()
    {
        //dd('home modulo core');
        return view('core::home.index');
    }
}
