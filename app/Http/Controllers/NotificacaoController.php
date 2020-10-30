<?php

namespace App\Http\Controllers;

use Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificacaoController extends Controller
{
    
    public function index() {
        return view('notifications.index');
    }


    public function markAllAsRead(Request $_request) {
        try {
            Auth::user()->unreadNotifications->markAsRead();
            Helper::setNotify('Notificações marcadas como lidas com sucesso!', 'success|check-circle');
        } catch (\Exception $th) {
            Helper::setNotify('Ops, tivemos um problema ao atualizar as notificações. Por favor, contate o suporte técnico!', 'danger|close-circle');
        }
        
        return redirect()->back()->withInput();
    }

}
