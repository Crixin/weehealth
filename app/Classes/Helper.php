<?php

namespace App\Classes;

use Session;
use App\Classes\Constants;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\{Auth, Log};
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\{Grupo, Empresa, Parametro, EmpresaUser, EmpresaGrupo};
use App\Repositories\{GrupoUserRepository, EmpresaGrupoRepository, EmpresaRepository, EmpresaUserRepository};

class Helper
{
    
    /**
     * Define as propriedades da notificação na sessão para utilizar o componente 'alert' (/resources/views/componentes/alert.blade.php)
     */
    public static function setNotify($_message, $_style) {
        Session::flash('message', $_message);
        Session::flash('style', $_style);
    }


    /**
     * Retornará true se o parâmetro referente ao 'identificador_parametro' enviado tiver a coluna 'ativo' = TRUE ou false caso contrário
     */
    public static function isParamActive($_key) {
        $param = Parametro::where('identificador_parametro', $_key)->first();
        return $param->ativo;
    }


    /**
     * Retornará o valor que deve ser exibido na tela. Caso o usuário tenha preenchido um valor customizado ('valor_usuario'), esse será o retorno. Se não, o valor padrão ('valor_padrao') será utilizado.
     */
    public static function getParamValue($_key) {
        $param = Parametro::where('identificador_parametro', $_key)->first();
        return ( !empty($param->valor_usuario) ) ? $param->valor_usuario : $param->valor_padrao;
    }


    /**
     * Captura e retorna todas as empresas às quais o USUÁRIO está vinculado DIRETAMENTE
     */
    public static function getUserEnterprises() {
        return Auth::user()->enterprises()->select('nome')->orderBy('nome')->get();
    }


    /**
     * Disponibiliza todos os GRUPOS aos quais o USUÁRIO está vinculado
     */
    public static function getUserGroups() {
        return Auth::user()->groups()->select('nome')->orderBy('nome')->get();
    }


    /**
     * Retorna o nome de todos os processos que o usuário tem permissão para serem exibidos no menu lateral
     * 
     * [OBS] Um usuário pode ter acesso aos processos através de seu vínculo com grupos ou por estar diretamente vinculado à empresas (mais que uma)
     */
    public static function getUserProcesses() {
        $roles = array();
        $empresas = array();

        if( Auth::user()->utilizar_permissoes_nivel_usuario ) {
            
            $roles = Auth::user()->enterprises->unique('id');
            foreach ($roles as $key => $value) {
                $processos = array();
                foreach ($value->processes as $key2 => $value2) {

                    // Verificação especial para os processos de upload de documento externo ('Documentos Diversos'), que possuem uma permissão especial dentro do sistema
                    if($value2->nome == Constants::$PROCESSOS[2]) {
                        if($value->pivot->permissao_upload_doc) {
                            $value2['id_area_ged'] = $value2->pivot->id_area_ged;
                            $processos[] = $value2;
                        }
                    } else {
                        $value2['id_area_ged'] = $value2->pivot->id_area_ged;
                        $processos[] = $value2;
                    }
                }
                $value['processos'] = $processos;
            }
        } else {
            
            foreach (Auth::user()->groups as $kGrupo => $grupo) {
                foreach( $grupo->enterprises as $kEmpresa => $empresa  ) {
                    $empresaAtual = Empresa::find($empresa->id);
                    $empresaAtual['permissao_upload_doc'] = $empresa->pivot->permissao_upload_doc;
                    $empresas[] = $empresaAtual;
                }
            }
            
            $roles = collect($empresas)->unique('id');
            foreach ($roles as $key => $value) {
                $processos = array();
                foreach ($value->processes as $key2 => $value2) {
                    
                    // Verificação especial para os processos de upload de documento externo ('Documentos Diversos'), que possuem uma permissão especial dentro do sistema
                    if($value2->nome == Constants::$PROCESSOS[2]) {
                        if($value->permissao_upload_doc) {
                            $value2['id_area_ged'] = $value2->pivot->id_area_ged;
                            $processos[] = $value2;
                        }
                    } else {
                        $value2['id_area_ged'] = $value2->pivot->id_area_ged;
                        $processos[] = $value2;
                    }
                }
                $value['processos'] = $processos;
            }
        }

        return $roles;
    }


    /**
     * Irá retornar todos os usuários vinculados à empresa (enviada por parâmetro) que possuem a permissão para receber e-mails ('permissao_receber_email' = TRUE). 
     *  Esses usuários podem estar vinculados diretamente com a empresa (permissão à nível de usuário) ou podem fazer parte de um grupo que está vinculado com a empresa.
     */
    public static function getEnterpriseRecipients(int $_idEmpresa) {
        try {
            $retorno       = null;
            $destinatarios = array();
            $empresa = Empresa::findOrFail($_idEmpresa);

            $usuariosVinculadosDiretamente = $empresa->users()
                                            ->wherePivot('permissao_receber_email', true)->where('utilizar_permissoes_nivel_usuario', true)
                                            ->get()->pluck('id')->toArray();
            array_push($destinatarios, $usuariosVinculadosDiretamente);
            
            $gruposVinculadosDiretamente = $empresa->groups()->wherePivot('permissao_receber_email', true)->get();
            foreach ($gruposVinculadosDiretamente as $key => $grupo) {
                $usuariosVinculadosPeloGrupo = $grupo->users()->where('utilizar_permissoes_nivel_usuario', false)->get()->pluck('id')->toArray();
                array_push($destinatarios, $usuariosVinculadosPeloGrupo);
            }

            $retorno = Arr::collapse($destinatarios);

        } catch (\Throwable $th) {
            Log::error(Constants::$LOG . "Ocorreu um erro ao buscar os destinatários da empresa com id {$_idEmpresa}!");
        } catch (ModelNotFoundException $mnfe) {
            Log::error(Constants::$LOG . "A empresa com id {$_idEmpresa} não foi encontrada para enviar e-mails!");
        }

        return $retorno;
    }


    /**
     * Mètodo para exibir o tamanho do arquivo de maneira agradável ao usuário
     *  
     * Snippet from PHP Share: http://www.phpshare.org
     */
    public static function formatSizeUnits($bytes) {
        if ($bytes >= 1073741824)
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        elseif ($bytes >= 1048576)
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        elseif ($bytes >= 1024)
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        elseif ($bytes > 1)
            $bytes = $bytes . ' bytes';
        elseif ($bytes == 1)
            $bytes = $bytes . ' byte';
        else
            $bytes = '0 bytes';

        return $bytes;
    }


    /**
     * Função que irá retornar se o parâmetro enviado está marcado como 'ativo' no sistema atual, ou seja, se o cliente "contratou/utiliza este módulo"
     */
    public static function isParamEnabled($_identificadorParam) {
        return Parametro::where('identificador_parametro', $_identificadorParam)->first()->ativo;
    }


    /**
     * Para identificar se o usuário tem a permissão para realizar as ações que são parâmetros do sistema dinamicamente.
     */
    public static function userCan($_sysActionParam, $_idEnterprise) {
        $retorno = false;
        if( Auth::user()->utilizar_permissoes_nivel_usuario ) {

            $permissao = EmpresaUser::where('empresa_id', $_idEnterprise)->where('user_id', Auth::user()->id)->first()[$_sysActionParam];
            if( !empty($permissao) ) $retorno = true;
        } else {
            
            $gruposDaEmpresa = Empresa::find($_idEnterprise)->groups->pluck('id')->toArray();
            $gruposDoUsuario = Auth::user()->groups->pluck('id')->toArray();
            $matches = array_intersect($gruposDaEmpresa, $gruposDoUsuario);
            if( count($matches) > 1 ) {

                // O usuário atual está vinculado a mais de um grupo que estão vinculados à empresa atual. Ex.: o usuário está no grupo 1 e 2 e os grupos 1 e 2 estão vinculados à empresa 1
                $grupos = Grupo::join('empresa_grupo', 'empresa_grupo.grupo_id', '=', 'grupo.id')->select($_sysActionParam)->whereIn('grupo.id', $matches)->get()->pluck($_sysActionParam);
                return $grupos->contains(true);
            } else {

                $permissao = EmpresaGrupo::where('empresa_id', $_idEnterprise)->where( 'grupo_id', $matches[key($matches)] )->first()[$_sysActionParam];

                // False é considerado vazio também...
                if( !empty($permissao) ) $retorno = true;
            }
        }
        return $retorno;
    }


    /**
     * Esse método deve ser utilizado em qualquer parte do sistema, quando for necessário verificar se o usuário logado atualmente pode ter acesso à qualquer uma das funcionalidades disponibilizadas como parâmetro do sistema.
     * Por si só, esse método verifica se o sistema utiliza a função atualmente (ex.: se a empresa não deseja excluir usuário, a função exclusão estará desabilitada) e, caso utilize, se o usuário tem a permissão de acessá-la.
     */
    public static function isEnabled($_identificadorParam, $_nomeColunaPermissionamento, $_idEmpresa) {
        
        if( Helper::isParamEnabled($_identificadorParam) ) {
            if( Helper::userCan($_nomeColunaPermissionamento, $_idEmpresa) ) {
                return true;
            }
        }

        return false;
    }


    /**
     * Método criado para ler o arquivo .ini de configuração do portal
     * 
     * @param $section = seção específica do arquivo 
     */
    public static function getInitialConfigs($section = '') {
        if( empty($section) )
            return parse_ini_file(storage_path('app/portal_conferencia.ini'), true);
        else 
            return parse_ini_file(storage_path('app/portal_conferencia.ini'), true)[$section];
    }


    /**
     * Concatena os valores IP, Porta e Caminho Base atuais do portal para acessar o FTP centralizado
     */
    public static function getClientFTP() {
        $ip = Parametro::where('identificador_parametro', 'FTP_IP')->first(); 
        $porta = Parametro::where('identificador_parametro', 'FTP_PORTA')->first(); 
        $caminho_base = Parametro::where('identificador_parametro', 'FTP_CAMINHO_BASE')->first(); 

        $ip = ( !empty($ip->valor_usuario) ) ? $ip->valor_usuario : $ip->valor_padrao;
        $porta = ( !empty($porta->valor_usuario) ) ? $porta->valor_usuario : $porta->valor_padrao;
        $caminho_base = ( !empty($caminho_base->valor_usuario) ) ? $caminho_base->valor_usuario : $caminho_base->valor_padrao;

        return $ip . ':' . $porta . $caminho_base;
    }


    /**
     * Estiliza uma string que é recebida por parâmetro e DEVE possuir : (dois pontos) para que o explode funcione.
     *     Caso contrário, a parte referida será exibida como '' (em branco);
     */
    public static function stylizeString($_string)
    {
        if (empty($_string)) {
            return "<span></span>";
        }
        
        $parts = explode(':', $_string);
        $label   = is_null($parts[0]) ? '' : $parts[0];
        $content = is_null($parts[1]) ? '' : $parts[1];
        
        return "$label:<span class='font-weight-bold'>$content</span>";
    }


    /**
     * Retorna o caminho base do FTP do sistema.
     */
    public static function getFTPBasePath() {
        return Helper::getParamValue('FTP_IP') . ':' . Helper::getParamValue('FTP_PORTA') . Helper::getParamValue('FTP_CAMINHO_BASE');
    }

    
    public static function validaCPF($cpf)
    {
        // Extrai somente os números
        $cpf = preg_replace('/[^0-9]/is', '', $cpf);
         
        // Verifica se foi informado todos os digitos corretamente
        if (strlen($cpf) != 11) {
            return false;
        }
        // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }
        // Faz o calculo para validar o CPF
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }
        return true;
    }
    

    public static function cleanString(string $_string)
    {
        return iconv('UTF-8', 'ASCII//TRANSLIT', $_string);
    }


    public static function buscaIndicesComumAreasGED(array $_areas = [])
    {
        try {
            $listaIndices = array();

            $ged = new RESTServices();

            foreach ($_areas as $key => $area) {
                $indices = $ged->buscaInfoArea($area)['response'][0]->listaIndicesRegistro ?? false;
                if (!$indices) {
                    throw new \Exception("Erro na pesquisa");
                }
                foreach ($indices as $key => $indice) {
                    if ($indice->utilizadoParaBusca) {
                        $listaIndices[] = serialize([
                            'idAreaReferenciada' => $indice->idAreaReferenciada,
                            'descricao' => $indice->descricao,
                            'idTipoIndice' => $indice->idTipoIndice,
                            'identificador' => $indice->identificador,
                            'listaMultivalorado' => $indice->listaMultivalorado,
                            'preenchimentoObrigatorio' => $indice->preenchimentoObrigatorio,
                            'exibidoNaPesquisa' => $indice->exibidoNaPesquisa
                        ]);
                    }
                }
            }

            $listaIndices = array_count_values($listaIndices);

            $listaIndices = array_filter($listaIndices, function ($arr) use ($_areas) {
                return $arr == count($_areas);
            });

            foreach ($listaIndices as $key => $indice) {
                $listaFinalIndices[] = unserialize($key);
            }

            return ['error' => false, 'response' => ['listaIndices' => $listaFinalIndices]];
        } catch (\Throwable $th) {
            return ['error' => true, 'response' => null];
        }
    }

    public static function getProcessesByUserAccess()
    {
        $empresasGrupo = array();

        $empresaRepository = new EmpresaRepository();
        $grupoUserRepository = new GrupoUserRepository();
        $empresaUserRepository = new EmpresaUserRepository();
        $empresaGrupoRepository = new EmpresaGrupoRepository();

        $grupo = $grupoUserRepository->findBy([['user_id', '=', Auth::user()->id]])[0] ?? null;
        
        if ($grupo) {
            $empresasGrupo = $empresaGrupoRepository->findBy([["grupo_id", '=', $grupo->grupo_id]])
            ->pluck('empresa_id');
        }

        $empresasUser = $empresaUserRepository->findBy([['user_id', '=', Auth::user()->id]])->pluck('empresa_id');

        $empresasArray = $grupo ? $empresasUser->merge($empresasGrupo)->toArray() : $empresasUser;

        $empresas = $empresaRepository->findBy([['id', '', $empresasArray, "IN"]], ['processes']);
        
        return $empresas;
    }
}
