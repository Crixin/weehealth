<?php

namespace App\Repositories;

use App\EdicaoDocumento;
use App\Repositories\BaseRepository\BaseRepository;
use Illuminate\Support\Facades\Auth;

class EdicaoDocumentoRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new EdicaoDocumento();
    }

    public function deleteRegAndDocument($endereco, $path)
    {
        $user_id = Auth::user()->id;
        try {
            $consultaDocumento = $this->findBy([['documento_nome', '=', $endereco,'and'],['user_id', '=', $user_id]]);
            foreach ($consultaDocumento as $key => $value) {
                $this->delete($value->id);
            }
            $countUserEditDocumento = $this->findBy([['documento_nome', '=', $endereco]]);
            if (file_exists($path) && count($countUserEditDocumento) == 0) {
                unlink($path);
            }
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
}