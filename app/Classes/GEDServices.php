<?php

namespace App\Classes;

use Illuminate\Support\Facades\Log;
use SoapVar;
use SoapClient;

class GEDServices
{
    protected $serverGED;
    protected $idUserGED;
    
    /*
    * Construtor
    */
    public function __construct($_settings)
    {
        $this->serverGED = $_settings['server'];
        $this->idUserGED = $_settings['id_user'];
    }


    /*
    * Getters e Setters
    */
    public function getServerGED()
    {
        return $this->serverGED;
    }

    public function setServerGED($_serverGED)
    {
        $this->serverGED = $_serverGED;
    }

    public function getIdUserGED()
    {
        return $this->idUserGED;
    }

    public function setIdUserGED($_idUserGED)
    {
        $this->idUserGED = $_idUserGED;
    }


    /*
    * Métodos
    */
    private function getType($_idTipoIndice)
    {
        switch ($_idTipoIndice) {
            case 1:
                return "boolean";
                break;
            case 2:
                return "long";
                break;
            case 3:
                return "string";
                break;
            case 4:
                return "long";
                break;
            case 5:
                return "string";
                break;
            case 6:
                return "string";
                break;
            case 7:
                return "boolean";
                break;
            case 8:
                return "int";
                break;
            case 9:
                return "string";
                break;
            case 10:
                return "string";
                break;
            case 11:
                return "string";
                break;
            case 12:
                return "string";
                break;
            case 13:
                return "string";
                break;
            case 14:
                return "string";
                break;
            case 15:
                return "string";
                break;
            case 16:
                return "string";
                break;
            case 17:
                return "string";
                break;
            case 18:
                return "string";
                break;
        }
    }


    private function buildIndexList($_arrIndices)
    {
        $listaIndices = [];

        for ($i = 0; $i < count($_arrIndices); $i++) {
            array_push(
                $listaIndices,
                array(
                    'alteravel' => true,
                    'exibidoNaPesquisa' => true,
                    'exportavel' => false,
                    'ordem' => 1,
                    'preenchimentoHabilitado' => true,
                    'preenchimentoObrigatorio' => true,
                    'protegidoPeloSistema' => true,
                    'somenteCadastro' => false,
                    'tamanho' => 150,
                    'unico' => true,
                    'utilizadoParaAssociacao' => false,
                    'utilizadoParaBusca' => true,

                    // Quando o GED foi atualizado para a v17.1.1
                    'exibidoNoEmail' => false,
                    'ordenavel' => true,
                    
                    'descricao' => $_arrIndices[$i]['descricao'],
                    'idTipoIndice' => $_arrIndices[$i]['idTipoIndice'],
                    'identificador' => $_arrIndices[$i]['identificador'],
                    'valor' => new SoapVar(
                        $_arrIndices[$i]['valor'],
                        XSD_STRING,
                        $this->getType($_arrIndices[$i]['idTipoIndice']),
                        'http://www.w3.org/2001/XMLSchema'
                    )
                )
            );
        }

        return $listaIndices;
    }


    public function pesquisaRegistros($_idArea, $_arrIndices, $_limMinimo, $_limMaximo)
    {
        $listaIndices = $this->buildIndexList($_arrIndices);

        // requisição para pesquisar registros no GED
        $request = array('pesquisarRegistros' => array(
                'arg0' => $this->idUserGED,
                'arg1' => $_idArea,
                'arg3' => $listaIndices,
                'arg4' => $_limMinimo,
                'arg5' => $_limMaximo
            )
        );

        
        // pesquisa registros no ged
        $options = array('location' => str_replace('?wsdl', '', $this->serverGED));
        $client = new SoapClient($this->serverGED);
        try {
            $resultado = $client->__soapCall('pesquisarRegistros', $request, $options);
            if ($resultado->return->totalResultadoPesquisa > 0) {
                $registros = $resultado->return->listaRegistro;
                return $registros;
            } else {
                return null;
            }
        } catch (\Throwable $th) {
            Log::error(Constants::$LOG . $th->getMessage());
            return null;
        }
    }


    public function insereRegistro($_arrIndices, $_idArea, $_listaDocumentos)
    {
        $listaIndices = $this->buildIndexList($_arrIndices);
        
        // requisição para inserir registro no GED
        $request = array('inserirRegistro' => array(
            'arg0' => array(
                'idArea' => $_idArea,
                'idUsuario' => $this->idUserGED,
                'listaDocumento' => $_listaDocumentos,
                'listaIndice' => $listaIndices,
                'removido' => false
            )
        ));

        // insere registro no ged
        $options = array('location' => str_replace('?wsdl', '', $this->serverGED));
        $client = new SoapClient($this->serverGED, array('encoding' => 'ISO-8859-1'));
        try {
            $result = $client->__soapCall('inserirRegistro', $request, $options);
            return $result->return;
        } catch (\Throwable $th) {
            Log::error(Constants::$LOG . $th->getMessage());
            return null;
        }
    }


    public function pesquisaRegistro($_idArea, $_idRegistro, $_dadosDocumentos, $_indicesDocumento)
    {
        // requisição para pesquisar um único registro completo no GED
        $request = array('pesquisarRegistro' => array(
            'arg0' => $_idArea,
            'arg1' => $_idRegistro,
            'arg2' => $_dadosDocumentos,
            'arg3' => $_indicesDocumento,
            )
        );

        // pesquisa registro específico no ged
        $options = array('location' => str_replace('?wsdl', '', $this->serverGED));
        $client = new SoapClient($this->serverGED);
        try {
            $resultado = $client->__soapCall('pesquisarRegistro', $request, $options);
            return $resultado;
        } catch (\Throwable $th) {
            Log::error(Constants::$LOG . $th->getMessage());
            return null;
        }
    }


    public function insereDocumento($_arrIndicesDoc, $_bytes, $_nomeArquivo, $_idArea, $_idRegistro, $_removido)
    {
        $listaIndices = $this->buildIndexList($_arrIndicesDoc);

        // requisição para inserir um novo documento em um registro do GED
        $request = array('inserirDocumento' => array(
                'arg0' => array (
                    'bytes'         => $_bytes,
                    'endereco'      => $_nomeArquivo,
                    'idArea'        => $_idArea,
                    'idRegistro'    => $_idRegistro,
                    'removido'      => $_removido,
                    'idUsuario'     => $this->idUserGED,
                    'listaIndice'   => $listaIndices
                )
            )
        );
    
        // insere documento no ged
        $options = array('location' => str_replace('?wsdl', '', $this->serverGED));
        $client = new SoapClient($this->serverGED, array('encoding' => 'ISO-8859-1'));
        try {
            $resultado = $client->__soapCall('inserirDocumento', $request, $options);

            if (!empty($resultado->return)) {
                return $resultado->return;
            } else {
                return null;
            }
        } catch (\Throwable $th) {
            Log::error(Constants::$LOG . $th->getMessage());
            return null;
        }
    }


    public function alterarDocumento(
        $_arrIndicesDoc,
        $_bytes,
        $_nomeArquivo,
        $_idDocumento,
        $_idArea,
        $_idRegistro,
        $_removido
    ) {
        $listaIndices = $this->buildIndexList($_arrIndicesDoc);

        // requisição para alterar/atualizar um documento já existente no GED
        $request = array('alterarDocumento' => array(
                'arg0' => array (
                    'bytes'         => $_bytes,
                    'endereco'      => $_nomeArquivo,
                    'id'            => $_idDocumento,
                    'idArea'        => $_idArea,
                    'idRegistro'    => $_idRegistro,
                    'idUsuario'     => $this->idUserGED,
                    'listaIndice'   => $listaIndices,
                    'removido'      => $_removido
                )
            )
        );
    
        // altera documento no ged
        $options = array('location' => str_replace('?wsdl', '', $this->serverGED));
        $client = new SoapClient($this->serverGED, array('encoding' => 'ISO-8859-1'));
        try {
            $resultado = $client->__soapCall('alterarDocumento', $request, $options);

            if (!empty($resultado->return)) {
                return $resultado->return;
            } else {
                return null;
            }
        } catch (\Throwable $th) {
            Log::error(Constants::$LOG . $th->getMessage());
            return null;
        }
    }


    public function alterarEmailRegistro($_idRegistro, $_assunto, $_mensagem, $_destinatarios)
    {
        // altera o e-mail de notificação automática de um registro do GED (usando o campo Data Validada)
        $request = array('alterarEmailRegistro' => array(
                'arg0' => $this->idUserGED,
                'arg1' => $_idRegistro,
                'arg2' => $_assunto,
                'arg3' => $_mensagem,
                'arg4' => $_destinatarios
            )
        );

        // altera e-mail de notificação automática
        $options = array('location' => str_replace('?wsdl', '', $this->serverGED));
        $client = new SoapClient($this->serverGED);
        try {
            $resultado = $client->__soapCall('alterarEmailRegistro', $request, $options);

            if (!empty($resultado->return)) {
                return $resultado->return;
            } else {
                return null;
            }
        } catch (\Throwable $th) {
            Log::error(Constants::$LOG . $th->getMessage());
            return null;
        }
    }


    public function removerDocumento($_idDocumento)
    {
        // requisição para remover um documento do GED
        $request = array('removerDocumento' => ['arg0' => $_idDocumento] );

        $options = array('location' => str_replace('?wsdl', '', $this->serverGED));
        $client = new SoapClient($this->serverGED);
        try {
            $resultado = $client->__soapCall('removerDocumento', $request, $options);
            return $resultado;
        } catch (\Throwable $th) {
            Log::error(Constants::$LOG . $th->getMessage());
            return null;
        }
    }


    public function pesquisaDocumento($_idDocumento)
    {
        // requisição para pesquisar um documento específico do GED
        $request = array('pesquisarDocumento' => ['arg0' => $_idDocumento] );

        $options = array('location' => str_replace('?wsdl', '', $this->serverGED));
        $client = new SoapClient($this->serverGED);
        try {
            $resultado = $client->__soapCall('pesquisarDocumento', $request, $options);
            return $resultado;
        } catch (\Throwable $th) {
            Log::error(Constants::$LOG . $th->getMessage());
            return null;
        }
    }


    public function pesquisarArea($_idArea)
    {
        // requisição para pesquisar uma área específica do GED
        $request = array('pesquisarArea' => ['arg0' => $_idArea] );

        $options = array('location' => str_replace('?wsdl', '', $this->serverGED));
        $client = new SoapClient($this->serverGED);
        try {
            $resultado = $client->__soapCall('pesquisarArea', $request, $options);
            return $resultado;
        } catch (\Throwable $th) {
            Log::error(Constants::$LOG . $th->getMessage());
            return null;
        }
    }




    // --------------------


    public function atualizaRegistro($_idRegistro, $_idArea, $_arrIndices)
    {
        $listaIndices = $this->buildIndexList($_arrIndices);
        
        // requisição para atualizar registro no GED
        $request = array('alterarRegistro' => array(
            'arg0' => array(
                'id' => $_idRegistro,
                'idArea' => $_idArea,
                'idUsuario' => env('ID_GED_USER'),
                'listaDocumento' => array(),
                'listaIndice' => $listaIndices,
                'removido' => false
            )
        ));

        // atualiza registro especificado no ged
        $options = array('location' => str_replace('?wsdl', '', $this->serverGED));
        $client = new SoapClient($this->serverGED);
        try {
            $registroAtualizado = $client->__soapCall('alterarRegistro', $request, $options);
            return $registroAtualizado;
        } catch (\Throwable $th) {
            Log::error(Constants::$LOG . $th->getMessage());
            return null;
        }
    }


    public function removeRegistro($_idRegistro)
    {
        // requisição para remover registro específico do GED
        $request = array('removerRegistro' => array(
                'arg0' => $_idRegistro
            )
        );

        // remove registro específico do GED
        $options = array('location' => str_replace('?wsdl', '', $this->serverGED));
        $client = new SoapClient($this->serverGED);
        try {
            $removido = $client->__soapCall('removerRegistro', $request, $options);
            return $removido;
        } catch (\Throwable $th) {
            Log::error(Constants::$LOG . $th->getMessage());
            return false;
        }
    }
 

    public function validaLoginRetornaId($_user, $_senha)
    {
        // requisição para validar login de usuário
        $request = array('validarLoginRetornaId' => array(
                'arg0' => $_user,
                'arg1' => $_senha
            )
        );

        // pesquisa registros no ged
        $options = array('location' => str_replace('?wsdl', '', $this->serverGED));
        $client = new SoapClient($this->serverGED);
        try {
            $validade = $client->__soapCall('validarLoginRetornaId', $request, $options);
            dd($validade);
            return $validade;
        } catch (\Throwable $th) {
            Log::error(Constants::$LOG . $th->getMessage());
            return null;
        }
    }
}
