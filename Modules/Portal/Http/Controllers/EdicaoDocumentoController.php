<?php

namespace Modules\Portal\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Portal\Repositories\EdicaoDocumentoRepository;

class EdicaoDocumentoController extends Controller
{

    protected $edicaoDocumentoRepository;

    public function __construct(EdicaoDocumentoRepository $edicaoDocumento)
    {
        $this->edicaoDocumentoRepository = $edicaoDocumento;
    }


    public function index()
    {

        $documentos = $this->edicaoDocumentoRepository->findBy([['user_id', '=', Auth::id()]]);

        return view("edicaoDocumento.index", compact('documentos'));
    }

    public function deleteRegistroAndDoc(Request $request)
    {
        $retorno = $this->edicaoDocumentoRepository->deleteRegAndDocument($request->endereco, $request->path);
        echo true;
    }
}
