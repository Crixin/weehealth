<?php

namespace Modules\Portal\Http\Controllers;

use Illuminate\Http\Request;
use App\Classes\{Constants, Helper, RESTServices};
use Modules\Portal\Repositories\{EmpresaProcessoRepository};

class GedController extends Controller
{
    private $ged;
    private $empresaProcessoRepository;

    /*
    * Construtor
    */
    public function __construct()
    {
        $this->ged = new RESTServices();
        $this->empresaProcessoRepository = new EmpresaProcessoRepository();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $empresasProcessos = Helper::getProcessesByUserAccess();
        $tipoIndicesGED = Constants::$OPTIONS_TYPE_INDICES_GED;
        $tipo = "create";

        return view('portal::ged.default', compact('empresasProcessos', 'tipoIndicesGED', 'tipo'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $_request)
    {
        $empresaProcessoInfo = $this->empresaProcessoRepository->find($_request->empresaProcesso);
        $indicesProcesso = json_decode($empresaProcessoInfo->todos_filtros_pesquisaveis);

        $indices = [];

        foreach ($indicesProcesso as $key => $indiceProc) {
            $identificador = $indiceProc->identificador;
            $indices[] = (object) [
                'idTipoIndice' => $indiceProc->idTipoIndice,
                'identificador' => $indiceProc->identificador,
                'valor' => $_request->$identificador
            ];
        }

        $areaGed = json_decode($empresaProcessoInfo->id_area_ged)[0];

        $newRegister = [
            "idArea" => $areaGed,
            "removido" => false,
            "listaIndice" => $indices
        ];

        $idRegistro = $this->ged->postRegistro($newRegister);
        if ($idRegistro['error']) {
            Helper::setNotify(
                'Ops, tivemos um problema criar o registro. Por favor, verifique os campos preenchidos, caso o erro persista contate o suporte!',
                'danger|close-circle'
            );
            return back();
        }

        $idRegistro = $idRegistro['response'];

        if ($_request->arquivo_upload) {
            foreach ($_request->arquivo_upload as $file) {
                $base64file = base64_encode(file_get_contents($file));

                $insereDocumento = [
                    'endereco' => $file->getClientOriginalName(),
                    'idArea' => $areaGed,
                    'idRegistro' => $idRegistro,
                    'idUsuario' => env('ID_GED_USER'),
                    'removido' => false,
                    'bytes'    => $base64file
                ];

                $response = $this->ged->postDocumento($insereDocumento);

                if ($response['error']) {
                    Helper::setNotify(
                        'Ops, tivemos um problema ao adicionar o arquivo ' . $file->getClientOriginalName() . ' . Por favor, contate o suporte!',
                        'danger|close-circle'
                    );
                    return back();
                }
            }
        }
        Helper::setNotify(
            'Registro criado com sucesso!',
            'success|check-circle'
        );
        return back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $_request)
    {
        $empresaProcessoSelected = $_request->empresaProcesso;

        $registro = $this->ged->getRegistro($_request->idRegistro);

        if ($registro['error']) {
            Helper::setNotify(
                'Ops, tivemos um problema ao acessar o registro. Por favor, contate o suporte!',
                'danger|close-circle'
            );
            return back();
        }

        $registro = $registro['response'];
        $idRegistro = $registro->id;

        $empresasProcessos = Helper::getProcessesByUserAccess();
        $tipoIndicesGED = Constants::$OPTIONS_TYPE_INDICES_GED;
        $tipo = "edit-form";

        $indicesRegistro = [];

        foreach ($registro->listaIndice as $key => $indice) {
            $indice->valor = $indice->idTipoIndice == 5 ? date('Y-m-d', strtotime(str_replace('/', '-', $indice->valor))) : $indice->valor;

            $indicesRegistro[$indice->identificador] = $indice->valor;
        }

        return view(
            'portal::ged.default',
            compact(
                'empresasProcessos',
                'tipoIndicesGED',
                'tipo',
                'idRegistro',
                'indicesRegistro',
                'empresaProcessoSelected'
            )
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $_request)
    {
        $empresaProcessoInfo = $this->empresaProcessoRepository->find($_request->idEmpresaProcesso);
        $indicesProcesso = json_decode($empresaProcessoInfo->todos_filtros_pesquisaveis);

        $indices = [];

        foreach ($indicesProcesso as $key => $indiceProc) {
            $identificador = $indiceProc->identificador;
            $indices[] = (object) [
                'idTipoIndice' => $indiceProc->idTipoIndice,
                'identificador' => $indiceProc->identificador,
                'valor' => $_request->$identificador
            ];
        }

        $areaGed = json_decode($empresaProcessoInfo->id_area_ged)[0];

        $parametros = [
            'id' => $_request->idRegistro,
            'idArea' => $areaGed,
            'listaIndice' => $indices,
            'idUsuario' => env('ID_GED_USER'),
            'removido' => false
        ];

        $update = $this->ged->putRegistro($parametros);

        if ($update['error']) {
            Helper::setNotify(
                'Ops, tivemos um problema ao atualizar o registro. Por favor, contate o suporte!',
                'danger|close-circle'
            );
            return back();
        }

        Helper::setNotify(
            'Registro atualizado com sucesso!',
            'success|check-circle'
        );
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function searchView(Request $_request)
    {
        $empresasProcessos = Helper::getProcessesByUserAccess();
        $tipoIndicesGED = Constants::$OPTIONS_TYPE_INDICES_GED;
        $tipo = "search-view";

        return view(
            'portal::ged.default',
            compact(
                'empresasProcessos',
                'tipoIndicesGED',
                'tipo'
            )
        );
    }

    public function search(Request $_request)
    {

        if (count($_request->all()) == 1) {
            $_request = (object) json_decode(session('cacheSearchRegister'));
        } else {
            session()->put('cacheSearchRegister', json_encode($_request->all()));
            $_request = (object) $_request->all();
        }

        $empresaProcessoInfo = $this->empresaProcessoRepository->find($_request->empresaProcesso);
        $indicesProcesso = json_decode($empresaProcessoInfo->todos_filtros_pesquisaveis);

        $empresaProcessoId = $empresaProcessoInfo->id;


        $indices = $this->prepareIndices($indicesProcesso, $_request);

        $areaGed = json_decode($empresaProcessoInfo->id_area_ged);

        $busca = [
            'listaIdArea' => $areaGed,
            'listaIndice' => $indices,
            'inicio' => 0,
            'fim' => 1000
        ];

        $resultado = $this->ged->buscaRegistros($busca);

        if ($resultado['error']) {
            Helper::setNotify(
                'Ops, tivemos um problema ao pesquisar os registros. Por favor, contate o suporte!',
                'danger|close-circle'
            );
            return back();
        }

        $registros = $resultado['response']->listaRegistro;

        $cabecalho = $indicesProcesso;
        $cabecalho = array_filter($cabecalho, function ($arr) {
            return $arr->exibidoNaPesquisa ?? false;
        });

        $cabecalho = array_column($cabecalho, "descricao");

        foreach ($registros as $key => $registro) {
            array_map(function ($indice) {
                if ($indice->idTipoIndice == 1) {
                    $indice->valor = ($indice->valor) ? "Sim" : "Não";
                }
            }, $registro->listaIndice);

            $registro->listaIndice = array_filter($registro->listaIndice, function ($indice) use ($cabecalho) {
                return in_array($indice->descricao, $cabecalho);
            });
        }

        return view('portal::ged.result_search', compact('registros', 'cabecalho', 'empresaProcessoId'));
    }


    public function listDocument(string $_empresaProcesso, string $_idRegistro)
    {
        $registro = $this->ged->getRegistro($_idRegistro, ["docs" => "true"]);

        if ($registro['error']) {
            Helper::setNotify(
                'Ops, tivemos um problema ao pesquisar os documentos. Por favor, contate o suporte!',
                'danger|close-circle'
            );
            return back();
        }

        $cabecalho = [];
        $documentos = [];
        $registro = $registro['response'];
        $idArea = $registro->idArea;

        if (!empty($registro->listaDocumento)) {
            $possuiDocumento = true;
            $documentos = (!is_array($registro->listaDocumento)) ? array($registro->listaDocumento) : $registro->listaDocumento;

            foreach ($documentos as $key => $value) {
                $value->listaIndice = array_filter($value->listaIndice, function ($v, $k) {
                    return !in_array($v->identificador, Constants::$INDICES_OCULTOS);
                }, ARRAY_FILTER_USE_BOTH);
            }

            $aux = $documentos;
            $cabecalho = array_shift($aux)->listaIndice;
        }

        $cabecalho = array_map(function ($arr) {
            return $arr->descricao;
        }, $cabecalho);

        $idRegistro = $_idRegistro;
        $empresaProcesso = $_empresaProcesso;

        return view('portal::ged.result_search', compact('documentos', 'cabecalho', 'idRegistro', 'idArea', 'empresaProcesso'));
    }


    public function accessDocument(string $_empresaProcesso, string $idDocumento)
    {
        $documento = $this->ged->get(env('GED_URL') . "/documento/" . $idDocumento)['response'];
        $idRegistro = $documento->idRegistro;
        $empresaProcesso = $_empresaProcesso;

        $documento->listaIndice = array_filter($documento->listaIndice, function ($v, $k) use ($documento) {
            if ($v->identificador == 'status') {
                if (!empty($v->valor)) {
                    $documento->status = $v->valor;
                }
            }
            if ($v->identificador == 'Nome_do_documento') {
                if (!empty($v->valor)) {
                    $documento->tipo = explode(".", $v->valor)[1];
                    $documento->endereco = $v->valor;
                }
            }
            return !in_array($v->identificador, Constants::$INDICES_OCULTOS);
        }, ARRAY_FILTER_USE_BOTH);


        $extensao_onlyoffice = Constants::$EXTENSAO_ONLYOFFICE;
        $extensao_imagem     = Constants::$EXTENSAO_IMAGEM;
        $extensao_video      = Constants::$EXTENSAO_VIDEO;
        $valor_doc_aprovado  = Constants::$VALOR_DOCUMENTO_APROVADO;

        if (in_array($documento->tipo, $extensao_onlyoffice)) {
            $pathFiles = public_path('plugins/onlyoffice-php/Storage');
            $nameFile = $documento->id . "_" . $documento->endereco;
            $documento->endereco = $nameFile;
            if (!file_exists($pathFiles . "/" . $nameFile)) {
                $file = base64_decode($documento->bytes);
                $path = $pathFiles . "/" . $nameFile;
                file_put_contents($path, $file);
            }
        }

        $permissoes['usa_download'] = false;
        $permissoes['usa_excluir'] = false;
        $permissoes['usa_aprovar'] = false;
        $permissoes['usa_imprimir'] = false;
        $permissoes['usa_editar'] = false;

        return view(
            'portal::ged.access-document',
            compact(
                'documento',
                'permissoes',
                'idRegistro',
                'empresaProcesso',
                'extensao_onlyoffice',
                'extensao_imagem',
                'extensao_video',
                'valor_doc_aprovado'
            )
        );
    }


    public function createDocuments(Request $_request)
    {
        foreach ($_request->arquivo_upload as $file) {
            $base64file = base64_encode(file_get_contents($file));

            $insereDocumento = [
                'endereco' => $file->getClientOriginalName(),
                'idArea' => $_request->idArea,
                'idRegistro' => $_request->idRegistro,
                'idUsuario' => env('ID_GED_USER'),
                'removido' => false,
                'bytes'    => $base64file
            ];

            $response = $this->ged->postDocumento($insereDocumento);

            if ($response['error']) {
                Helper::setNotify(
                    'Ops, tivemos um problema ao adicionar o arquivo ' . $file->getClientOriginalName() . ' . Por favor, contate o suporte!',
                    'danger|close-circle'
                );
                return back();
            }
        }

        Helper::setNotify(
            'Documentos inseridos com sucesso!',
            'success|check-circle'
        );
        return back();
    }


    private function prepareIndices($_indices, $_valores)
    {
        $listaIndices = [];

        foreach ($_indices as $indice) {
            $identificador = $indice->identificador;
            if ($_valores->$identificador ?? false) {
                //VALIDAÇÃO TIPO BOOLEAN
                if ($indice->idTipoIndice == 1) {
                    $value = filter_var($_valores->$identificador, FILTER_VALIDATE_BOOLEAN);

                    $listaIndices[] = (object) [
                        'idTipoIndice' => $indice->idTipoIndice,
                        'identificador' => $indice->identificador,
                        'valor' => $value
                    ];
                //VALIDAÇÃO TIPO VINCULO
                } elseif ($indice->idTipoIndice == 17) {
                    $areaReferenciada = $this->ged->buscaInfoArea($indice->idAreaReferenciada);

                    if ($areaReferenciada['error']) {
                        return false;
                    }

                    $areaReferenciada = $areaReferenciada['response'][0];

                    foreach ($areaReferenciada->listaIndicesRegistro as $key => $refIndice) {
                        if ($indice->identificador == $refIndice->identificador) {
                             $infoIndiAreaRef = $refIndice;
                        }
                    }

                    $params = [
                        'listaIdArea' => [$indice->idAreaReferenciada],
                        'listaIndice' => [
                            (object) [
                                'idTipoIndice' => $infoIndiAreaRef->idTipoIndice,
                                'identificador' => $infoIndiAreaRef->identificador,
                                'valor' => $_valores->$identificador
                            ]
                        ],
                        'inicio' => 0,
                        'fim' => 3000
                    ];

                    $buscaIdReg = $this->ged->pesquisaRegistro($params);

                    if ($buscaIdReg['error']) {
                        return false;
                    }
                   //Seta o id do registro para pesquisar na área de vínculo
                    $_valores->$identificador = $buscaIdReg['response']->listaRegistro[0]->id;

                    $listaIndices[] = (object) [
                        'idTipoIndice' => $indice->idTipoIndice,
                        'identificador' => $indice->identificador,
                        'valor' => is_numeric($_valores->$identificador) ? (int) $_valores->$identificador : $_valores->$identificador
                    ];
                } else {
                    $listaIndices[] = (object) [
                        'idTipoIndice' => $indice->idTipoIndice,
                        'identificador' => $indice->identificador,
                        'valor' =>  is_numeric($_valores->$identificador) ? (int) $_valores->$identificador : $_valores->$identificador
                    ];
                }
            }
        }
        return $listaIndices;
    }
}
