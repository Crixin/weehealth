<?php

namespace Modules\Core\Http\Controllers;

use App\Classes\Helper;
use Illuminate\Http\Request;
use Notification;
use Illuminate\Support\Facades\Auth;

class NotificacaoController extends Controller
{
    public function markAllAsRead(Request $_request)
    {
        try {
            Auth::user()->unreadNotifications->markAsRead();
            Helper::setNotify('Notificações marcadas como lidas com sucesso!', 'success|check-circle');
        } catch (\Exception $th) {
            Helper::setNotify('Ops, tivemos um problema ao atualizar as notificações. Por favor, contate o suporte técnico!', 'danger|close-circle');
        }
        return redirect()->back()->withInput();
    }

}
