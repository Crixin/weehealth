<?php

namespace Modules\Core\Services;

use App\Classes\Helper;
use Modules\Core\Model\User;
use App\Services\ValidacaoService;
use Exception;
use Illuminate\Support\Facades\{DB, Validator};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Modules\Core\Repositories\UserRepository;
use Modules\Docs\Repositories\AgrupamentoUserDocumentoRepository;
use Modules\Docs\Repositories\DocumentoRepository;
use Modules\Docs\Repositories\UserEtapaDocumentoRepository;
use Modules\Docs\Services\AgrupamentoUserDocumentoService;
use Modules\Docs\Services\UserEtapaDocumentoService;

class UserService
{

    private $rules;
    private $userRepository;
    protected $userEtapaDocumentoRepository;
    protected $agrupamentoUserDocumentoRepository;
    protected $documentoRepository;

    public function __construct()
    {
        $user = new User();
        $this->rules = $user->rules;
        $this->userRepository = new UserRepository();
        $this->userEtapaDocumentoRepository = new UserEtapaDocumentoRepository();
        $this->agrupamentoUserDocumentoRepository = new AgrupamentoUserDocumentoRepository();
        $this->documentoRepository = new DocumentoRepository();
    }

    public function store(Request $request)
    {
        try {
            $validacao = new ValidacaoService($this->rules, $request->all());
            $errors = $validacao->make();

            if ($errors) {
                return ["success" => false, "redirect" => redirect()->back()->withErrors($errors)->withInput()];
            }

            $criacao = $this->montaRequest($request);

            DB::transaction(function () use ($criacao) {
                $usuario  = $criacao['username'];
                $password = $criacao['password'];
                $busca = DB::select("SELECT count(*) as total FROM pg_roles WHERE rolname ILIKE '" . $usuario . "'");
                if ($busca[0]->total == 0) {
                    $userAux = '"' . $usuario . '"';
                    DB::purge(getenv('DB_CONNECTION'));
                    Config::set('database.connections.pgsql.username', getenv('DB_USERNAME'));
                    Config::set('database.connections.pgsql.password', getenv('DB_PASSWORD'));
                    DB::reconnect(getenv('DB_CONNECTION'));
                    $cria   = DB::select("CREATE ROLE $userAux WITH LOGIN NOSUPERUSER INHERIT NOCREATEDB NOCREATEROLE NOREPLICATION VALID UNTIL 'infinity' ");
                    $altera = DB::unprepared("ALTER USER $userAux WITH PASSWORD '" . $password . "'");
                    $setFrupo = DB::unprepared("GRANT weehealth TO $userAux ");
                }
                $usuario = $this->userRepository->create($criacao);
            });

            return ["success" => true];
        } catch (\Throwable $th) {
            dd($th);
            return ["success" => false ,"redirect" => redirect()->back()];
        }
    }

    public function update(Request $request)
    {
        try {
            $update = $this->montaRequest($request);
            $this->rules['username'] .= "," . $update['id'];
            $this->rules['email'] .= "," . $update['id'];

            unset($this->rules['password']);
            $validacao = new ValidacaoService($this->rules, $request->all());
            $errors = $validacao->make();
            if ($errors) {
                return ["success" => false, "redirect" => redirect()->back()->withErrors($errors)->withInput()];
            }
            DB::beginTransaction();
                $this->userRepository->update($update, $update['id']);
            DB::commit();
            return ["success" => true];
        } catch (\Throwable $th) {
            DB::rollback();
            return ["success" => false, "redirect" => redirect()->back()];
        }
    }

    /** [
     *  array documentos,
     *  int user_id,
     *  int grupo_id,
     *  int user_substituto_id
     * ] */
    public function replaceUserDoc(array $data)
    {
        try {
            DB::transaction(function () use ($data) {

                //Aprovadores
                $buscaUserEtapaDocumento = $this->userEtapaDocumentoRepository->findBy(
                    [
                        ['documento_id', '', $data['documentos'], "IN"],
                        ['user_id', '=', $data['user_id'], "AND"],
                        ['grupo_id', '=', $data['grupo_id'], "AND"]
                    ]
                );
                foreach ($buscaUserEtapaDocumento as $key => $value) {

                    $buscaDocumento = $this->documentoRepository->find($value->documento_id);

                    if ($buscaDocumento->revisao == $value->documento_revisao) {
                        $arrayUserEtapaDocumento = [];
                        $arrayUserEtapaDocumento['grupo_user_etapa'][$key] = [
                            "user_id"           => (int) $data['user_substituto_id'],
                            "grupo_id"          => $value->grupo_id,
                            "etapa_fluxo_id"    => $value->etapa_fluxo_id,
                        ];
                        $arrayUserEtapaDocumento['documento_id'] = $value->documento_id;
                        $arrayUserEtapaDocumento['documento_revisao'] = $buscaDocumento->revisao;

                        $userEtapaDocumentoService = new UserEtapaDocumentoService();
                        if (!$userEtapaDocumentoService->delete((array) $value->id)['success']) {
                            throw new Exception("Erro ao deletar o vinculo com o usuário.", 1);
                        }

                        if (!$userEtapaDocumentoService->store($arrayUserEtapaDocumento)['success']) {
                            throw new Exception("Erro ao desvincular usuário.", 1);
                        }
                    }
                }


                //Grupo Treinamento/Divulgacao
                $buscaGrupoTreinamentoDivulgacao = $this->agrupamentoUserDocumentoRepository->findBy(
                    [
                        ['documento_id', '', $data['documentos'], "IN"],
                        ['user_id', '=', $data['user_id'], "AND"],
                        ['grupo_id', '=', $data['grupo_id'], "AND"],
                        ['documento_revisao', '=', $buscaDocumento->revisao, "AND"]
                    ]
                );
                foreach ($buscaGrupoTreinamentoDivulgacao as $key => $value) {
                    $buscaDocumento = $this->documentoRepository->find($value->documento_id);

                    if ($buscaDocumento->revisao == $value->documento_revisao) {
                        $arrayAgrupamento = [];
                        $arrayAgrupamento['grupo_and_user'][$key] = [
                            "user_id"      => (int) $data['user_substituto_id'],
                            "grupo_id"     => $value->grupo_id,
                            "tipo"         => $value->tipo
                        ];
                        $arrayAgrupamento['documento_id'] = $value->documento_id;
                        $arrayAgrupamento['documento_revisao'] = $buscaDocumento->revisao;

                        $agrupamentoUserDocumentoService = new AgrupamentoUserDocumentoService();

                        if (!$agrupamentoUserDocumentoService->delete((array) $value->id)['success']) {
                            throw new Exception("Erro ao deletar o vinculo com o usuário.", 1);
                        }

                        if (!$agrupamentoUserDocumentoService->store($arrayAgrupamento)['success']) {
                            throw new Exception("Erro ao desvincular usuário.", 1);
                        }
                    }
                }
            });
            return ["success" => true];
        } catch (\Throwable $th) {
            dd($th);
            Helper::setNotify("Erro ao desvincular usuário. " . __("messages.contateSuporteTecnico"), 'danger|close-circle');
            return ["success" => false, "redirect" => redirect()->back()->withInput()];
        }
    }

    public function inativate(array $data, int $id)
    {
        try {
            $operacao = "ativado";
            if ($data['inativo'] == 1) {
                $operacao = "inativado";
                DB::beginTransaction();
                //FORCE TO CHECK FOREING KEY
                $this->userRepository->forceDelete($id);
                DB::rollBack();
            }

            $this->userRepository->update($data, $id);
            DB::commit();

            return ["success" => true, "message" => "Usuário " . $operacao . " com sucesso!"];
        } catch (\Throwable $th) {
            DB::rollBack();
            return ["success" => false, "message" => 'Erro ao inativar o usuário. Verifique os aprovadores ou os documentos associados ao usuário.'];
        }
    }

    public function montaRequest(Request $request)
    {
        $retorno = [
            "name"          => $request->get('name'),
            "username"      => $request->get('username'),
            "email"         => $request->get('email'),
            "perfil_id"     => $request->get('perfil'),
            "setor_id"      => $request->get('setor'),
            'utilizar_permissoes_nivel_usuario' => false,
            'password'      => bcrypt($request->get('password')),
            'administrador' => false,
        ];

        if ($request->foto) {
            $mimeType = $request->file('foto')->getMimeType();
            $imageBase64 = base64_encode(file_get_contents($request->file('foto')->getRealPath()));
            $imageBase64 = 'data:' . $mimeType . ';base64,' . $imageBase64;
            $retorno['foto'] = $imageBase64;
        }

        if ($request->idUsuario) {
            unset($retorno['password']);
            $retorno['id'] = $request->get('idUsuario');
        }
        return $retorno;
    }

    public function updateUserPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:6|confirmed'
        ]);

        if ($validator->fails()) {
            Helper::setNotify($validator->messages()->first(), 'danger|close-circle');
            return ["success" => false, "redirect" => redirect()->back()->withErrors($validator->fails())->withInput()];
        }

        DB::beginTransaction();
        try {
                $senha = bcrypt($request->get('password'));
                $userRepository = new UserRepository();
                $update = $userRepository->update(['password' => $senha], $request->get('idUsuario'));
                DB::commit();

                DB::purge(getenv('DB_CONNECTION'));
                Config::set('database.connections.pgsql.username', getenv('DB_USERNAME'));
                Config::set('database.connections.pgsql.password', getenv('DB_PASSWORD'));
                DB::reconnect(getenv('DB_CONNECTION'));
                $buscaUsuario = $this->userRepository->find($request->get('idUsuario'));
                $userAux = '"' . $buscaUsuario->username . '"';

                $altera = DB::unprepared("ALTER USER $userAux WITH PASSWORD '" . $senha . "'");
                return ["success" => true];
        } catch (\Throwable $th) {
            DB::rollback();
            return ["success" => false, "redirect" => redirect()->back()];
        }
    }
}
