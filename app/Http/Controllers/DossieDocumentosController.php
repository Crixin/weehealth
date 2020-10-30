<?php

namespace App\Http\Controllers;

use Helper;
use Illuminate\Http\Request;
use App\Http\Controllers\JobController;
use App\Http\Controllers\Auth\JWTController;
use Illuminate\Support\Facades\{Auth, Storage, DB, File};
use Illuminate\Filesystem\Filesystem;
use App\Classes\{Constants, RESTServices};
use App\Repositories\{
    GrupoUserRepository,
    EmpresaGrupoRepository,
    EmpresaUserRepository,
    EmpresaRepository,
    DossieRepository,
    DossieEmpresaProcessoRepository,
    ParametroRepository,
    EmpresaProcessoRepository
};

class DossieDocumentosController extends Controller
{
    protected $grupoUserRepository;
    protected $empresaGrupoRepository;
    protected $empresaUserRepository;
    protected $empresaRepository;
    protected $empresaProcessoRepository;
    protected $dossieRepository;
    protected $dossieEmpresaProcessoRepository;
    protected $parametroRepository;
    protected $JWT;

    private $ged;

    /**
    * Construtor
    */
    public function __construct(
        GrupoUserRepository $grupoUser,
        EmpresaGrupoRepository $empresaGrupo,
        EmpresaUserRepository $empresaUserGrupo,
        EmpresaRepository $empresa,
        DossieRepository $dossie,
        DossieEmpresaProcessoRepository $dossieEmpresaProcesso,
        ParametroRepository $parametro,
        EmpresaProcessoRepository $empresaProcesso
    ) {
        $this->grupoUserRepository = $grupoUser;
        $this->empresaGrupoRepository = $empresaGrupo;
        $this->empresaUserRepository = $empresaUserGrupo;
        $this->empresaRepository = $empresa;
        $this->dossieRepository = $dossie;
        $this->dossieEmpresaProcessoRepository = $dossieEmpresaProcesso;
        $this->parametroRepository = $parametro;
        $this->empresaProcessoRepository = $empresaProcesso;
        $this->ged = new RESTServices();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function novo()
    {
        $empresas = Helper::getProcessesByUserAccess();
        $tiposIndicesGED = Constants::$OPTIONS_TYPE_INDICES_GED;
        $pocHavan = $this->parametroRepository->findOneBy([["identificador_parametro", '=', 'POC_HAVAN']]);

        return view('dossieDocumentos.index', compact('empresas', 'tiposIndicesGED', 'pocHavan'));
    }
    
    
    public function list()
    {
        $dossies = $this->dossieRepository->findAll();
        return view('dossieDocumentos.sended_list', compact('dossies'));
    }

    public function downloadDossie(Request $_request)
    {
        $filters = json_decode($_request->filters);
        $app_path = storage_path('app');

        $nomeAreas = [];
        $empresaProcessos = [];
        //HAVAN BEGIN
        $pocHavan = $this->parametroRepository->findOneBy([["identificador_parametro", '=', 'POC_HAVAN']]);
        //HAVAN END

        if ($_request->filters != "{}") {
            $zip = new \ZipArchive();

            if ($_request->titulo) {
                $fileName = 'app/' . $_request->titulo . '_' . date('d-m-Y_H-i-s') . '.zip';
            } else {
                $fileName = 'app/' . 'Dossiê-De-Documentos_' . date('d-m-Y_H-i-s') . '.zip';
            }
            
            $folderName = uniqid("download");
            
            Storage::makeDirectory($folderName);

            $pathFiles = $app_path . '/' . $folderName;
            $contDatas = 0;
            
            foreach ($filters as $area => $indices) {
                $listaIndices = [];
                
                foreach ($indices as $indice) {
                    //VALIDAÇÃO TIPO BOOLEAN
                    if ($indice->idTipoIndice == 1) {
                        $indice->valor = boolval($indice->valor);
                    
                        $listaIndices[] = (object) [
                            'idTipoIndice' => $indice->idTipoIndice,
                            'identificador' => $indice->identificador,
                            'valor' => is_numeric($indice->valor) ? (int) $indice->valor : $indice->valor
                        ];
                    } elseif (in_array($indice->idTipoIndice, [5, 6])) {
                        $contDatas++;

                        $valorDatas = $contDatas == 1 ? $indice->valor : $valorDatas . ';' . $indice->valor;

                        if ($contDatas == 2) {
                            $listaIndices[] = (object) [
                                'idTipoIndice' => $indice->idTipoIndice,
                                'identificador' => $indice->identificador,
                                'valor' => $valorDatas
                            ];
                            $contDatas = 0;
                        }
                    } elseif ($indice->idTipoIndice == 17) {
                        $areaReferenciada = $this->ged->buscaInfoArea($indice->idAreaReferenciada);

                        if ($areaReferenciada['error']) {
                            Helper::setNotify(
                                'Ops, tivemos um problema ao pesquisar a área vínculo no GED. Por favor, contate o suporte técnico!',
                                'danger|close-circle'
                            );
                            return back();
                        }

                        $areaReferenciada = $areaReferenciada['response'][0];

                        foreach ($areaReferenciada->listaIndicesRegistro as $key => $refIndice) {
                            if ($indice->identificador == $refIndice->identificador) {
                                $infoIndiAreaRef = $refIndice;
                            }
                        }

                        $params = [
                            'listaIdArea' => [
                                $indice->idAreaReferenciada
                            ],
                            'listaIndice' => [
                                (object) [
                                    'idTipoIndice' => $infoIndiAreaRef->idTipoIndice,
                                    'identificador' => $infoIndiAreaRef->identificador,
                                    'valor' => $indice->valor
                                ]
                            ],
                            'inicio' => 0,
                            'fim' => 3000
                        ];

                        $buscaIdReg = $this->ged->pesquisaRegistro($params);

                        if ($buscaIdReg['error']) {
                            Helper::setNotify(
                                'Ops, tivemos um problema ao pesquisar um registro no GED. Por favor, contate o suporte técnico!',
                                'danger|close-circle'
                            );
                            return back();
                        }
                        //Seta o id do registro para pesquisar na área de vínculo
                        $indice->valor = $buscaIdReg['response']->listaRegistro[0]->id;
                    }
                    
                    $listaIndices[] = (object) [
                        'idTipoIndice' => $indice->idTipoIndice,
                        'identificador' => $indice->identificador,
                        'valor' => is_numeric($indice->valor) ? (int) $indice->valor : $indice->valor
                    ];
                }

                $params = [
                    'listaIdArea' => json_decode($area),
                    'listaIndice' => $listaIndices,
                    'inicio' => 0,
                    'fim' => 3000
                ];

                $result = $this->ged->pesquisaRegistro($params);

                if ($result['error']) {
                    Helper::setNotify(
                        'Ops, tivemos um problema ao buscar os documentos. Por favor, contate o suporte técnico!',
                        'danger|close-circle'
                    );
                    return back();
                }

                foreach (json_decode($area) as $key => $areaBusca) {
                    $info = $this->ged->buscaInfoArea($areaBusca)['response'][0];
                    $nomeAreas[$areaBusca] = [
                        "nome" => $info->nome
                    ];
                }

                $listaRegistros = $result['response']->listaRegistro;
                
                $filtro = [
                    ['processo_id', '=', $indices[0]->idProcesso],
                    ['empresa_id', '=', $indices[0]->idEmpresa]
                ];

                $infoProcesso = $this->empresaProcessoRepository->findOneBy($filtro, ['processo', 'empresa']);
                $nomeEmpresa = str_replace('/', '', Helper::cleanString($infoProcesso->empresa->nome));

                $empresaProcessos[] = $infoProcesso->id;

                $nomeProcesso = $infoProcesso->processo->nome;
                
                //HAVAN BEGIN
                $emailsHavan = [];
                //HAVAN END
                foreach ($listaRegistros as $key => $registro) {
                    //HAVAN BEGIN
                    foreach ($registro->listaIndice as $indice) {
                        if ($indice->identificador == "email") {
                            $emailsHavan[] = $indice->valor;
                        }
                    }
                    //HAVAN END
                    foreach ($registro->listaDocumento as $documento) {
                        $documentoGed = $this->ged->getDocumento($documento->id, ['docs' => 'true']);
                        $documentoGed = $documentoGed['response'];
                   
                        $file = base64_decode($documentoGed->bytes);
                        
                        $path = $pathFiles . "/" . $documento->endereco;
                        file_put_contents($path, $file);
            
                        if ($zip->open(storage_path($fileName), \ZipArchive::CREATE)) {
                            $files = \File::files($pathFiles);
                            foreach ($files as $key => $value) {
                                $relativeNameInZipFile = basename($value);
                            
                                switch ($_request->identificador) {
                                    case 'PASTA':
                                        $zip->addFile($value, $nomeEmpresa . "/PROCESSO-" . $nomeProcesso . '/AREA-' . $nomeAreas[$registro->idArea]['nome'] . '/' . $registro->id . '/' . $relativeNameInZipFile);
                                        
                                        break;

                                    case 'ARQUIVO':
                                        $zip->addFile($value, $nomeEmpresa . "/PROCESSO-" . $nomeProcesso . '/AREA-' . $nomeAreas[$registro->idArea]['nome'] . '/' . $registro->id . '_' . $relativeNameInZipFile);
                                        
                                        break;

                                    default:
                                        # code...
                                        break;
                                }
                            }
                            $zip->close();
                            $file = new Filesystem();
                            $file->cleanDirectory($pathFiles);
                        }
                    }
                }
            }

            Storage::deleteDirectory($folderName);

            if (file_exists(storage_path($fileName))) {
                //HAVAN BEGIN
                if ($pocHavan) {
                    $emailsHavan = array_unique($emailsHavan);
                    return $this->envioDossie($fileName, $_request->tipoEnvio, $emailsHavan, $_request->titulo, $empresaProcessos);
                } else {
                    $destinatarios = explode(",", $_request->destinatarios);
                    return $this->envioDossie($fileName, $_request->tipoEnvio, $destinatarios, $_request->titulo, $empresaProcessos);
                }

            } else {
                Helper::setNotify(
                    'Ops, Nenhum documento foi encontrado com os filtros selecionados!',
                    'warning|close-circle'
                );
                return back();
            }
        }
        Helper::setNotify(
            'Ops, Nenhum processo selecionado!',
            'warning|close-circle'
        );
        return back();
    }


    private function envioDossie($_fileName, $_tipoEnvio, $_destinatarios, $_titulo, $_empresaProcessos)
    {

        DB::beginTransaction();

        try {
            switch (true) {
                case is_numeric($_tipoEnvio):
                    $timer = intval($_tipoEnvio);

                    $emails = array();
                    $emails2JWT = array();

                    foreach ($_destinatarios as $key => $destinatario) {
                        $emails[] = [
                            'email' => $destinatario,
                            'downloaded' => false
                        ];
                        $emails2JWT[] = $destinatario;
                    }

                    $dossie = $this->dossieRepository->create([
                        'titulo' => $_titulo,
                        'status' => "DISPONÍVEL",
                        'caminho_documento' => $_fileName,
                        'destinatarios' => serialize($emails),
                        'validade' => date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' + ' . $timer . ' minute'))
                    ]);
                    
                    foreach ($_empresaProcessos as $key => $_empresaProcesso) {
                        $this->dossieEmpresaProcessoRepository->create([
                            'dossie_id' => $dossie->id,
                            'empresa_processo_id' => $_empresaProcesso
                        ]);
                    }

                    $info = [
                        'email' => $emails2JWT,
                        'dossie' => $dossie->id
                    ];
                    
                    $jwt = new JWTController();
                    $token = $jwt->generateToken($info, $timer);

                    $server = 'http' . (empty($_SERVER['HTTPS']) ? '' : 's') . '://' . $_SERVER['HTTP_HOST'];

                    $send = new JobController();
                    $send->enqueue($token, $emails2JWT, $server);

                    Helper::setNotify(
                        'E-mails enviados com sucessos!',
                        'success|check-circle'
                    );
                    DB::commit();
                    return back();

                    break;
                    
                case $_tipoEnvio === "DOWNLOAD":
                    return response()->download(storage_path($_fileName))->deleteFileAfterSend(true);
                    break;

                default:
                    # code...
                    break;
            }
        } catch (\Throwable $th) {
            DB::rollback();
            File::delete(storage_path($_fileName));
            Helper::setNotify(
                'Um erro ocorreu ao gravar o registro',
                'danger|close-circle'
            );
            return back();
        }

       // return response()->download(storage_path($pathArquivo))->deleteFileAfterSend(true);
    }


    public function downloadByLink(Request $_request)
    {
        $jwt = new JWTController();
        $authenticate = $jwt->authenticate($_request->token);
        $token = $_request->token;
        $disabled = false;
        
        if ($authenticate['error']) {
            $disabled = true;
            Helper::setNotify(
                'Seu link para download dos arquivos é inválido ou seu tempo já expirou. Solicite outro ao remetente do link',
                'warning|close-circle'
            );
            return view('dossieDocumentos.download', compact('disabled', 'token'));
        }
        $response = $authenticate['response'];
        
        if (in_array($_request->email, $response->email)) {
            $dossie = $this->dossieRepository->find($response->dossie);

            $destinatarios = unserialize($dossie->destinatarios);
            
            foreach ($destinatarios as $key => $destinatario) {
                if ($destinatario['email'] == $_request->email) {
                    $destinatarios[$key]['downloaded'] = true;
                }
            }

            $this->dossieRepository->update([
                'destinatarios' => serialize($destinatarios)
            ], $dossie->id);

            return response()->download(storage_path($dossie->caminho_documento));
        }
        
        Helper::setNotify(
            'O email informado não está autorizado para efetuar o download!',
            'warning|close-circle'
        );
        return view('dossieDocumentos.download', compact('disabled', 'token'));
    }


    public function verifyLink(Request $_request)
    {
        $jwt = new JWTController();
        $authenticate = $jwt->authenticate($_request->token);

        $disabled = false;
        $token = $_request->token;
        
        if ($authenticate['error']) {
            $disabled = true;
            Helper::setNotify(
                'Seu link para download dos arquivos é inválido ou seu tempo já expirou. Solicite outro ao remetente do link',
                'warning|close-circle'
            );
        }
        

        return view('dossieDocumentos.download', compact('disabled', 'token'));
    }
}
