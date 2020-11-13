<?php

namespace Modules\Core\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\SendEmail;
use Modules\Core\Http\Controllers\Controller;

class JobController extends Controller
{
    /**
     *
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function enqueue($_token, $_destinatarios, $_server)
    {
        foreach ($_destinatarios as $key => $destinatario) {
            SendEmail::dispatch([
                'email' => $destinatario,
                'token' => $_token,
                'server' => $_server,
            ]);
        }
    }
}
