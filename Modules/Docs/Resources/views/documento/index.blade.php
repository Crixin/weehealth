@extends('layouts.app')

@extends('layouts.menuDocs')
@yield('menu')

@section('content')
    <!-- O que fazer nesta situação? -->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    
    <link href="{{ asset('plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}"  rel="stylesheet">
    <script src="{{ asset('plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js') }}"></script>
    

    @if (session('approval_success'))
		<input type="hidden" name="status" id="status" value="approval_success">
    @elseif (session('reject_success'))
        <input type="hidden" name="status" id="status" value="reject_success">
    @elseif (session('link_success'))
		<input type="hidden" name="status" id="status" value="link_success">
    @elseif (session('resend_success'))
		<input type="hidden" name="status" id="status" value="resend_success">
    @elseif (session('import_list_success'))
		<input type="hidden" name="status" id="status" value="import_list_success">
    @elseif (session('start_review_success'))
		<input type="hidden" name="status" id="status" value="start_review_success">
    @elseif (session('start_short_review_success'))
		<input type="hidden" name="status" id="status" value="start_short_review_success">
    @elseif (session('document_name_already_exists'))
		<input type="hidden" name="status" id="status" value="document_name_already_exists">
    @elseif (session('cancel_review_success'))
		<input type="hidden" name="status" id="status" value="cancel_review_success">
    @elseif (session('cancel_short_review_success'))
		<input type="hidden" name="status" id="status" value="cancel_short_review_success">
    @elseif (session('make_obsolete_doc'))
		<input type="hidden" name="status" id="status" value="make_obsolete_doc">
    @elseif (session('make_active_doc'))
		<input type="hidden" name="status" id="status" value="make_active_doc">
    @elseif (session('fail_active_doc'))
		<input type="hidden" name="status" id="status" value="fail_active_doc">
    @elseif (session('update_info_success'))
		<input type="hidden" name="status" id="status" value="update_info_success">
    @elseif (session('error_update_doc'))
		<input type="hidden" name="status" id="status" value="error_update_doc">
    @elseif (session('delete_document_success'))
		<input type="hidden" name="status" id="status" value="delete_document_success">
    @elseif (session('delete_document_error'))
		<input type="hidden" name="status" id="status" value="delete_document_error">
    @endif

    <script>
        $(document).ready(function(){
            // Verifica se acabou de gravar uma nova solicitação
            var status = $("#status").val();
            if(status == "approval_success") {
                showToast('Sucesso!', 'O documento foi aprovado.', 'success');
            } else if(status == "reject_success") {
                showToast('Sucesso!', 'O documento foi rejeitado.', 'success');
            } else if(status == "resend_success") {
                showToast('Sucesso!', 'O documento foi reenviado para o setor Processos.', 'success');
            } else if(status == "link_success") {
                showToast('Sucesso!', 'A vinculação foi realizada com sucesso.', 'success');
            } else if(status == "import_list_success") {
                showToast('Sucesso!', 'A lista de presença foi salva.', 'success');
            } else if(status == "start_review_success") {
                showToast('Sucesso!', 'A revisão do documento foi iniciada.', 'success');
            } else if(status == "start_short_review_success") {
                showToast('Sucesso!', 'A validação do documento foi iniciada.', 'success');
            } else if(status == "document_name_already_exists") {
                showToast('Nome já existe!', 'Já existe um documento no sistema com esse mesmo nome. Por favor, escolha outro!', 'warning');
            } else if(status == "cancel_review_success") {
                showToast('Sucesso!', 'A revisão do documento foi cancelada com sucesso.', 'success');
            } else if(status == "cancel_short_review_success") {
                showToast('Sucesso!', 'A validação do documento foi cancelada com sucesso.', 'success');
            } else if(status == "make_obsolete_doc") {
                showToast('Sucesso!', 'O documento foi marcado como obsoleto. Você pode ativá-lo a qualquer momento!', 'success');
            } else if(status == "fail_active_doc") {
                showToast('Falhou!', 'O documento não foi reativado, verifique se o código ainda está diponível!', 'error');
            } else if(status == "make_active_doc") {
                showToast('Sucesso!', 'O documento foi ativado com sucesso!', 'success');
            } else if(status == "update_info_success") {
                showToast('Sucesso!', 'As informações do documento foram atualizadas com sucesso!', 'success');
            } else if(status == "error_update_doc") {
                showToast('Falhou!', 'As informações do documento não foram atualizadas!', 'error');
            } else if(status == "delete_document_success") {
                showToast('Sucesso!', 'O documento foi deletado!', 'success');
            } else if(status == "delete_document_error") {
                showToast('Falhou!', 'O documento não foi deletado!', 'error');
            }
        });
    </script>



    <!-- Page wrapper  -->
    <!-- ============================================================== -->
    <div class="page-wrapper">
        <!-- ============================================================== -->
        <!-- Container fluid  -->
        <!-- ============================================================== -->
        <div class="container-fluid">
            

            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <div class="row page-titles">
                <div class="col-md-5 col-8 align-self-center">
                    <h3 class="text-themecolor m-b-0 m-t-0">Documentação</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ URL::route('home') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Documentação</li>
                    </ol>
                </div>
                <div class="col-md-7 col-4 align-self-center">
                    <div class="">
                        <button class="right-side-toggle waves-light btn-success btn btn-circle btn-xl pull-right m-l-10   btn-badge badge-top-right" data-count="{{ count(\App\Classes\Helpers::instance()->getNotifications( Auth::user()->id )) }}">
                            <i class="ti-comment-alt text-white"></i>
                        </button>
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            
            
            <!-- ============================================================== -->
            <!-- Start Page Content -->
            <!-- ============================================================== -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs nav-fill customtab" role="tablist">
                                <li class="nav-item"> <a class="nav-link font-bold" data-toggle="tab" href="#gerarDocs" role="tab"><h3 class="hidden-xs-down">GERAR DOCUMENTOS</h3></a> </li>
                                <li class="nav-item"> <a class="nav-link font-bold active" data-toggle="tab" href="#visualizarDocs" role="tab"><h3 class="hidden-xs-down">VISUALIZAR DOCUMENTOS</h3></a> </li>
                            </ul>
                            <!-- Tab panes -->
                            <div class="tab-content">

                                <!-- 
                                    /* TAB - Gerar Documento */ 
                                -->
                                <div class="tab-pane p-20" id="gerarDocs" role="tabpanel">
                                    @if(Auth::user()->permissao_elaborador)
                                        <div class="col-md-12">
                                            {!! Form::open(['route' => 'documentacao.validate-data', 'class' => 'form-horizontal', 'id' => 'form-generate-document']) !!}
                                                <!-- Linha 1 -->
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <div class="col-md-10 control-label font-bold">
                                                                {!! Form::label('setor_dono_doc', 'Setor:') !!}
                                                            </div>
                                                            <div class="col-md-12">
                                                                {!! Form::select('setor_dono_doc', $sectorsAccess, key($setorUsuarioAtual), ['class' => 'form-control  custom-select']) !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <div class="col-md-6 control-label font-bold">
                                                                {!! Form::label('tipo_documento', 'TIPO DE DOCUMENTO:') !!}
                                                            </div>
                                                            <div class="col-md-12">
                                                                {!! Form::select('tipo_documento', $tipoDocumentos, '-- Selecione --', ['class' => 'form-control  custom-select']) !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Linha 2 -->
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <div class="col-md-10 control-label font-bold">
                                                                {!! Form::label('nivelAcessoDocumento', 'NÍVEL DE ACESSO AO DOCUMENTO:') !!}
                                                            </div>
                                                            <div class="col-md-12">
                                                                {!! Form::select('nivelAcessoDocumento', [Constants::$NIVEL_ACESSO_DOC_LIVRE, Constants::$NIVEL_ACESSO_DOC_RESTRITO, Constants::$NIVEL_ACESSO_DOC_CONFIDENCIAL], '', ['class' => 'form-control  custom-select']) !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <div class="col-md-6 control-label font-bold">
                                                                {!! Form::label('aprovador', 'APROVADORES:') !!}
                                                            </div>
                                                            <div class="col-md-12">
                                                                {!! Form::select('aprovador', $aprovadoresSetorAtual, '', ['class' => 'form-control  custom-select']) !!}
                                                            </div>
                                                        </div>
                                                    </div>    
                                                </div>

                                                <!-- Linha 3 -->
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <div class="col-md-10 control-label font-bold">
                                                                {!! Form::label('areaInteresse', 'ÁREA DE INTERESSE:') !!}
                                                            </div>
                                                            <div class="col-md-12">
                                                                <select multiple id="optgroup-newAreaDeInteresse" name="areaInteresse[]">
                                                                    @foreach($setoresUsuarios as $key => $su)
                                                                        <optgroup label="{{ $key }}">
                                                                            @foreach($su as $key2 => $user)
                                                                                <option value="{{ $key2 }}">{{ $user }}</option>
                                                                            @endforeach
                                                                        </optgroup>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>   
                                                    </div>                                                    
                                                </div>

                                                <!-- Linha 4 -->
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <div class="col-md-10 control-label font-bold">
                                                                {!! Form::label('grupoTreinamentoDoc', 'GRUPO DE TREINAMENTO:') !!}
                                                            </div>
                                                            <div class="col-md-12">
                                                                <select multiple id="optgroup-newGrupoTreinamentoDoc" name="grupoTreinamentoDoc[]">
                                                                    @foreach($setoresUsuarios as $key => $su)
                                                                        <optgroup label="{{ $key }}">
                                                                            @foreach($su as $key2 => $user)
                                                                                <option value="{{ $key2 }}">{{ $user }}</option>
                                                                            @endforeach
                                                                        </optgroup>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>   
                                                    </div>                                                    
                                                </div>

                                                <!-- Linha 5 -->
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <div class="col-md-10 control-label font-bold">
                                                                {!! Form::label('grupoDivulgacaoDoc', 'GRUPO DE DIVULGAÇÃO:') !!}
                                                            </div>
                                                            <div class="col-md-12">
                                                                <select multiple id="optgroup-newGrupoDivulgacaoDoc" name="grupoDivulgacaoDoc[]">
                                                                    @foreach($setoresUsuarios as $key => $su)
                                                                        <optgroup label="{{ $key }}">
                                                                            @foreach($su as $key2 => $user)
                                                                                <option value="{{ $key2 }}">{{ $user }}</option>
                                                                            @endforeach
                                                                        </optgroup>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>   
                                                    </div>                                                    
                                                </div>

                                                <!-- Linha 6 --> 
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <div class="col-md-10 control-label font-bold">
                                                                {!! Form::label('validadeDocumento', 'VALIDADE DO DOCUMENTO:') !!}
                                                            </div>
                                                            <div class="col-md-12">
                                                                {!! Form::text('validadeDocumento', date('d/n/Y'), ['class' => 'form-control', 'id' => 'mdate']) !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <div class="col-md-10 control-label font-bold">
                                                                {!! Form::label('copiaControlada', 'CÓPIA CONTROLADA:') !!}
                                                            </div>
                                                            <div class="col-md-12">
                                                                <input name="copiaControlada" type="radio" id="sim" value="true" class="with-gap radio-col-blue" />
                                                                <label for="sim">Sim</label>
                                                                <input name="copiaControlada" type="radio" id="nao" value="false" class="with-gap radio-col-light-blue" checked/>
                                                                <label for="nao">Não</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>                                                

                                                <!-- Linha 7 -->
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <div class="col-md-6 control-label font-bold">
                                                                {!! Form::label('tituloDocumento', 'TÍTULO DO DOCUMENTO:') !!}
                                                            </div>
                                                            <div class="col-md-12">
                                                                {!! Form::text('tituloDocumento', null, ['class' => 'form-control']) !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <div class="col-md-10 control-label font-bold">
                                                                {!! Form::label('formulariosAtrelados', 'ATRELAR AOS FORMULÁRIOS:') !!}
                                                            </div>
                                                            <div class="col-md-12">
                                                                <select multiple id="optgroup-formulariosAtrelados" name="formulariosAtrelados[]" class="form-control select2" style="width:100%;">
                                                                    @foreach($formularios as $key => $form)
                                                                        <option value="{{ $key }}" >{{ explode(Constants::$SUFIXO_REVISAO_NOS_TITULO_DOCUMENTOS, $form)[0] }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>   
                                                    </div>           
                                                </div>

                                                <!-- Linha 8 -->
                                                <div class="row">
                                                    <div class="col-md-12 mb-4">
                                                        @if ($errors->any())
                                                            <div class="alert alert-danger">
                                                                <ul>
                                                                    @foreach ($errors->all() as $error)
                                                                        <li>{{ $error }}</li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        @elseif (session('document_name_already_exists'))
                                                            <div class="alert alert-warning">
                                                                <ul>
                                                                    <li>{{ session('document_name_already_exists') }}</li>
                                                                </ul>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    
                                                    <div class="col-md-6">
                                                        <div class="col-md-12">
                                                            <button type="button" id="importDocument" class="btn waves-effect waves-light btn-block btn-lg btn-secondary">IMPORTAR DOCUMENTO</button>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="col-md-12">
                                                            <button type="button" id="createDocument" class="btn waves-effect waves-light btn-block btn-lg btn-secondary">CRIAR DOCUMENTO</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                            {!! Form::close() !!}
                                        </div>
                                    @else
                                        <div class="col-md-12">
                                            <h3>Você não possui permissão para criar documentos. Por favor, contate seu superior.</h3>
                                        </div>
                                    @endif
                                </div>


                                <!-- 
                                    /* TAB - Visualizar Documento */ 
                                -->
                                <div class="tab-pane active" id="visualizarDocs" role="tabpanel">
                                    <div class="p-20">
                                        <div class="col-md-12">
                                            {{-- Aviso: prioridade do título do documento no filtro --}}
                                            <div class="row">
                                                <h5 class="alert alert-info alert-dismissible" role="alert">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    Quando o campo <b>Título do Documento</b> for preenchido, os outros filtros serão <b>ignorados</b>. Caso o campo seja deixado em branco, os outros filtros serão aplicados em conjunto.
                                                </h5>
                                            </div>

                                            {{-- FILTROS --}}
                                            <div class="row">
                                                <h4>FILTROS</h4>
                                                <div class="col-md-12">

                                                    {!! Form::open(['route' => 'documentacao.filter-documents-index', 'class' => 'form-horizontal', 'style' => 'width: 96%']) !!}
                                                        <div class="row" style="width: 109%">
                                                            <div class="col-md-4" >
                                                                <div class="row ">
                                                                    {!! Form::select('search_tipoDocumento', $tipoDocumentos, null, ['class' => 'form-control  custom-select', 'style' => 'width: 96%']) !!}
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="row">
                                                                    {!! Form::select('search_status', array(null => '- Status -') + $status, null, ['class' => 'form-control  custom-select', 'style' => 'width: 96%']) !!}
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="row">
                                                                    {!! Form::select('search_nivel_acesso', array(null => '- Nível de Acesso -') + $nivel_acesso, null, ['class' => 'form-control  custom-select', 'style' => 'width: 96%']) !!}
                                                                </div>
                                                            </div>
                                                        </div>  
                                                        <div class="row margin-top-1percent" style="width: 109%">
                                                            <div class="col-md-4">
                                                                <div class="row">
                                                                    {!! Form::select('search_setor', array(null => '- Setor -') + $setores, null, ['class' => 'form-control  custom-select', 'style' => 'width: 96%']) !!}
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="row">
                                                                    <div class="input-group" style="width: 96%">
                                                                        {!! Form::text('search_validadeDocumento', null, ['class' => 'form-control', 'id' => 'mdate_search', 'placeholder'=>'- Validade -']) !!}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-1"></div>
                                                            <div class="col-md-3">
                                                                <div class="row" style="margin-top: 4%;">
                                                                    <input type="checkbox" name="possuiCopiaControlada" id="ckbCopiaControlada" class="filled-in" {{($filtroCopiaControlada) ? 'checked' : ''}} />
                                                                    <label for="ckbCopiaControlada">Possui Cópia Controlada</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row margin-top-1percent" style="width: 108%">    
                                                            <div class="col-md-{{ (Auth::user()->setor_id == Constants::$ID_SETOR_QUALIDADE) ? '4' : '6'}}">
                                                                <div class="row">
                                                                    {!! Form::text('search_tituloDocumento', null, ['class' => 'form-control', 'placeholder' => '- Título do Documento -', 'style' => 'width: 95%; ']) !!}
                                                                </div>
                                                            </div>
                                                            <div class="col-md-{{ (Auth::user()->setor_id == Constants::$ID_SETOR_QUALIDADE) ? '8' : '6'}}">
                                                                <div class="row">
                                                                    
                                                                    @if( Auth::user()->setor_id == Constants::$ID_SETOR_QUALIDADE )
                                                                        <div class="col-md-3">
                                                                            <a href="{{ route('documentacao.pendentes_revisao') }}" class="btn btn-block waves-effect waves-light btn-outline-warning"><i class="mdi mdi-calendar-remove"></i> Pend. revisão</a>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <a href="{{ route('documentacao.obsoletos') }}" class="btn btn-block waves-effect waves-light btn-outline-danger"><i class="fa fa-expeditedssl"></i> Obsoletos</a>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <a href="{{ route('documentacao') }}" class="btn btn-block waves-effect waves-light btn-secondary"><i class="fa fa-ban"></i> Limpar</a>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <button type="submit" class="btn btn-block waves-effect waves-light btn-outline-success"><i class="fa fa-search"></i> Buscar</button>
                                                                        </div>
                                                                    @else
                                                                        <div class="col-md-4">
                                                                            <a href="{{ route('documentacao.pendentes_revisao') }}" class="btn btn-block waves-effect waves-light btn-outline-warning"><i class="mdi mdi-calendar-remove"></i> Pend. revisão</a>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <a href="{{ route('documentacao') }}" class="btn btn-block waves-effect waves-light btn-secondary"><i class="fa fa-ban"></i> Limpar</a>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <button type="submit" class="btn btn-block waves-effect waves-light btn-outline-success"><i class="fa fa-search"></i> Buscar</button>
                                                                        </div>
                                                                    @endif

                                                                </div>
                                                            </div>
                                                        </div>                                                        
                                                    {!! Form::close() !!} 
                                                    
                                                </div>
                                            </div>

                                            {{-- Aviso: pesquisa com datatable --}}
                                            <div class="row mt-5 margin-top-1percent">
                                                <h5 class="alert alert-warning alert-dismissible" role="alert">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    Para filtrar registros através do <b>Título do Documento</b>, utilize o campo acima. Qualquer outro campo pode ser pesquisado no campo <i>Pesquisar</i> (canto superior direito da tabela).
                                                </h5>
                                            </div>

                                            <div class="row mt-2 margin-top-1percent">
                                                <div class="table-responsive m-t-40">
                                                    <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-center text-nowrap">Ações</th>
                                                                <th class="text-center text-nowrap">Código</th>
                                                                <th class="text-center">Título do Documento</th>
                                                                <th class="text-center">Última Revisão</th>
                                                                <th class="text-center text-nowrap ">Revisão</th>
                                                                <th class="text-center">Status</th>
                                                                <th class="text-center">Nível Acesso</th>
                                                                {{-- <th class="text-center">Validade</th> --}}
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            
                                                            @if( $documentos_nao_finalizados != null && count($documentos_nao_finalizados) > 0 )
                                                                @foreach($documentos_nao_finalizados as $doc)
                                                                    <tr>
                                                                        <td class="text-nowrap text-center">
                                                                            <a href="{{ route('documentacao.print', ['id' => $doc->id]) }}" class="mr-3"> <i class="fa fa-print text-primary" data-toggle="tooltip" data-original-title="Imprimir"></i> </a>  

                                                                            @if( Auth::user()->setor_id == Constants::$ID_SETOR_QUALIDADE )
                                                                                <a href="{{ route('documentacao.presence-lists', ['id' => $doc->id]) }}" class="mr-3"> <i class="fa fa-file-text-o text-primary" data-toggle="tooltip" data-original-title="Ver Listas de Presença"></i> </a>     
                                                                            @endif
                                                                            
                                                                            @if( Auth::user()->setor_id == Constants::$ID_SETOR_QUALIDADE || (Auth::user()->id == $doc->elaborador_id && $doc->etapa_num == Constants::$ETAPA_WORKFLOW_ELABORADOR_NUM ) )
                                                                                <a href="{{ route('documentacao.edit-info', ['id' => $doc->id]) }}" class="mr-3"> <i class="fa fa-pencil text-success" data-toggle="tooltip" data-original-title="Editar Informações"></i> </a>
                                                                            @endif

                                                                            <a href="#" title="Vincular Formulários" data-forms="{{ $doc->formularios }}" data-id="{{ $doc->id }}" data-toggle="modal" data-target="#vinculos-form-modal" data-finalizado="false"><i class="fa fa-exchange text-info" data-toggle="tooltip" data-original-title="Vincular Formulários"></i></a>
                                                                        </td>

                                                                        <td class="text-center text-nowrap"> {{ $doc->codigo }} </td>

                                                                        {{ Form::open(['route' => 'documentacao.view-document', 'method' => 'POST', 'id'=>'view-documento-'.$doc->id]) }}
                                                                            {{ Form::hidden('document_id', $doc->id) }}
                                                                            <td class="text-center a-href-submit force-break-word text-nowrap">
                                                                                <p class="text-center a-href-submit force-break-word submit-view-documento" data-id="{{ $doc->id }}"> {{ explode(Constants::$SUFIXO_REVISAO_NOS_TITULO_DOCUMENTOS, $doc->nome)[0] }}</p>
                                                                            </td>
                                                                        {{ Form::close() }}

                                                                        <td class="text-center">{{ ($doc->data_revisao) ?  date("d/m/Y H:i:s", strtotime($doc->data_revisao)) :  '-' }}</td>

                                                                        <td class="text-center text-nowrap"> {{ $doc->revisao }} </td>

                                                                        <td class="text-center"><p class="text-muted font-weight-bold {{ ($doc->etapa == 'Finalizado') ? ' text-success' : '' }} "> {{ $doc->etapa }} </p></td>

                                                                        <td class="text-center">{{ $doc->nivel_acesso }}</td>

                                                                        {{-- <td class="text-center">{{ date("d/m/Y", strtotime($doc->validade)) }}</td> --}}
                                                                    </tr>
                                                                @endforeach
                                                            @endif

                                                            @if( $documentos_finalizados != null && count($documentos_finalizados) > 0 )
                                                                @foreach($documentos_finalizados as $docF)
                                                                    <tr>
                                                                        <td class="text-nowrap text-center">
                                                                            @if (Auth::user()->setor_id == Constants::$ID_SETOR_QUALIDADE || $docF->setor_id == Auth::user()->setor_id && Auth::user()->permissao_elaborador)
                                                                                <a href="javascript:void(0)" class="btn-open-confirm-short-review mr-3" data-id="{{ $docF->id }}"> <i class="fa fa-eye text-warning" style="color: brown !important" data-toggle="tooltip" data-original-title="Iniciar validação"></i> </a>
                                                                            @endif

                                                                            <a href="{{ route('documentacao.print', ['id' => $docF->id]) }}" class="mr-3"> <i class="fa fa-print text-primary" data-toggle="tooltip" data-original-title="Imprimir"></i> </a> 

                                                                            @if( Auth::user()->setor_id == Constants::$ID_SETOR_QUALIDADE )
                                                                                <a href="{{ route('documentacao.presence-lists', ['id' => $docF->id]) }}" class="mr-3"> <i class="fa fa-file-text-o text-primary" data-toggle="tooltip" data-original-title="Ver Listas de Presença"></i> </a>     
                                                                            
                                                                                <a href="{{ route('documentacao.edit-info', ['id' => $docF->id]) }}" class="mr-3"> <i class="fa fa-pencil text-success" data-toggle="tooltip" data-original-title="Editar Informações"></i> </a>     

                                                                                <a href="#" class="mr-3" data-forms="{{ $docF->formularios }}" data-id="{{ $docF->id }}" data-toggle="modal" data-target="#vinculos-form-modal" data-finalizado="true"><i class="fa fa-exchange text-info" data-toggle="tooltip" data-original-title="Vincular Formulários"></i></a>
                                                                            @endif

                                                                            @if (Auth::user()->setor_id == Constants::$ID_SETOR_QUALIDADE || $docF->setor_id == Auth::user()->setor_id && Auth::user()->permissao_elaborador)
                                                                                <a href="javascript:void(0)" class="btn-open-confirm-review mr-3" data-id="{{ $docF->id }}"> <i class="fa fa-eye text-warning" data-toggle="tooltip" data-original-title="Iniciar Revisão"></i> </a>																		
                                                                            @endif

                                                                            @if( Auth::user()->setor_id == Constants::$ID_SETOR_QUALIDADE )
                                                                                <a href="javascript:void(0)" class="btn-tornar-documento-obsoleto-modal" data-id="{{ $docF->id }}"> <i class="fa fa-power-off text-danger" data-toggle="tooltip" data-original-title="Tornar Obsoleto"></i> </a> 
                                                                            @endif
                                                                        </td>

                                                                        <td class="text-center text-nowrap"> {{ $docF->codigo }} </td>

                                                                        {{ Form::open(['route' => 'documentacao.view-document', 'method' => 'POST', 'id'=>'view-documento-'.$docF->id]) }}
                                                                            {{ Form::hidden('document_id', $docF->id) }}
                                                                            
                                                                            <td class="text-center a-href-submit force-break-word text-nowrap">
                                                                                <p class="text-center a-href-submit force-break-word submit-view-documento" data-id="{{ $docF->id }}"> {{ explode(Constants::$SUFIXO_REVISAO_NOS_TITULO_DOCUMENTOS, $docF->nome)[0] }}</p>
                                                                            </td>
                                                                        {{ Form::close() }}

                                                                        <td class="text-center">{{ ($docF->data_revisao) ?  date("d/m/Y H:i:s", strtotime($docF->data_revisao)) :  '-' }}</td>


                                                                        <td class="text-nowrap text-center"> {{ $docF->revisao }} </td>


                                                                        <td class="text-center"><p class="text-muted font-weight-bold text-success"> Finalizado </p> </td>

                                                                        <td class="text-center">{{ $docF->nivel_acesso }}</td>

                                                                        {{-- <td class="text-center">{{ date("d/m/Y", strtotime($docF->validade)) }}</td> --}}
                                                                    </tr>
                                                                @endforeach
                                                            @endif

                                                        </tbody>
                                                    </table>      
                                                </div>
                                            </div>
                                            
                                        </div> 
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- End Page Content -->
            <!-- ============================================================== -->
            
            <!-- Modal para vínculos com formulários -->
            <div id="vinculos-form-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                <div class="modal-dialog modal-lg">
                    
                    {{ Form::open(['route' => 'documentacao.save-link-form', 'id' => 'save-link-form', 'method' => 'POST']) }}
                        
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Vinculação de Formulários</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        <div class="modal-body">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="col-md-12 control-label font-bold">
                                        {!! Form::label('formulariosAtrelados', 'ATRELAR AOS FORMULÁRIOS:') !!}
                                    </div>
                                    <div class="col-md-12">
                                        <select multiple name="formulariosAtreladosDocs[]" class="form-control select2-vinculos" style="width:100%;">
                                            @foreach($formularios as $key => $form)
                                                <option value="{{ $key }}" >{{ explode(Constants::$SUFIXO_REVISAO_NOS_TITULO_DOCUMENTOS, $form)[0] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>   
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>
                            <button type="button" class="btn-save-link btn btn-success waves-effect" >Salvar Vínculos </button>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
            <!-- /.modal para vínculos com formulários -->


            <!-- Modal de confirmação - deseja mesmo iniciar uma revisão para este documento -->
            <div class="modal fade bs-example-modal-sm" id="iniciar-revisao-modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" style="display: none;">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-body"> 
                            <label id="label-revisao-modal"></label>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Não</button>

                            {{ Form::open(['route' => 'documentacao.start-review', 'method' => 'POST', 'id' => "form_review"]) }}
                                {{ Form::hidden('document_id', '', ['id' => 'document_id_request_review']) }}
                                <button type="submit" class="btn btn-success waves-effect"> Sim </button>
                            {!! Form::close() !!}
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.Modal de confirmação - deseja mesmo iniciar uma revisão para este documento -->


            <!-- Modal de confirmação - deseja mesmo tornar o documento obsoleto -->
            <div class="modal fade" id="tornar-documento-obsoleto-modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" style="display: none;">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-body"> 
                            Tem certeza que deseja tornar o documento obsoleto? 
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Não</button>

                            {{ Form::open(['route' => 'documentacao.make-obsolete-doc', 'method' => 'POST']) }}
                                {{ Form::hidden('doc_id', '', ['id' => 'form_id_make_obsolete_doc']) }}
                                <button type="submit" class="btn btn-success waves-effect"> Sim </button>
                            {!! Form::close() !!}
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.Modal de confirmação - deseja mesmo tornar o documento obsoleto -->


            <script>

                // Material Date picker   
                $('#mdate').bootstrapMaterialDatePicker({ weekStart : 0, time: false, minDate: new Date(), lang: 'pt-br', format: 'DD/M/YYYY', currentDate: new Date(), cancelText: 'Cancelar', okText: 'Definir' });
                $('#mdate_search').bootstrapMaterialDatePicker({ weekStart : 0, time: false, lang: 'pt-br', format: 'DD/M/YYYY',  cancelText: 'Cancelar', okText: 'Definir' }); //currentDate: new Date(),

                /*
                *   QUANDO CARREGAR A PÁGINA
                */
                $(document).ready(function(){
                    // Envia o form conforme o botão que foi clicado
                    $("#importDocument").click(function(){
                        var input = $("<input>").attr("type", "hidden").attr("name", "action").val("import");
                        $('#form-generate-document').append($(input));
                        $('#form-generate-document').submit();
                    });

                    $("#createDocument").click(function(){
                        var input = $("<input>").attr("type", "hidden").attr("name", "action").val("create");
                        $('#form-generate-document').append($(input));
                        $('#form-generate-document').submit();
                    });
                    
                    $("#setor_dono_doc").change(function(){
                        var obj = {'setor': $("#setor_dono_doc").val()};        
                        ajaxMethod('GET', "{{ URL::route('ajax.listaAprovadores.getAprovadores') }}", obj).then(function(result) {
                            $('#aprovador').empty();
                            $.each(result, function(key, value) {
                                $('#aprovador').append($('<option>', { value : key }).text(value));
                            });
                        }, function(err) {
                            console.log(err)
                        });
                    });

                    // Ao clicar no botão que abrirá o modal de confirmação para revisão do documento
                    $(".btn-open-confirm-review").click(function(){
                        var id = $(this).data('id');
                        $("#document_id_request_review").val(id);
                        $("#form_review").attr("action", "{{ route('documentacao.start-review') }}");
                        
                        $("#label-revisao-modal").text('Deseja iniciar uma revisão para este documento?');
                        $("#iniciar-revisao-modal").modal({
                            backdrop: 'static',
                            keyboard: false
                        });
                    });

                    $(".btn-open-confirm-short-review").click(function(){
                        var id = $(this).data('id');
                        $("#document_id_request_review").val(id);
                        
                        $("#label-revisao-modal").text('Deseja iniciar uma validação para este documento?');
						$("#form_review").attr("action", "{{ route('documentacao.start-short-review') }}");

                        $("#iniciar-revisao-modal").modal({
                            backdrop: 'static',
                            keyboard: false
                        });
                    });

                    // Ao clicar no botão que abrirá o modal de confirmação para tornar o formulário obsoleto
                    $(".btn-tornar-documento-obsoleto-modal").click(function(){
                        var id = $(this).data('id');
                        $("#form_id_make_obsolete_doc").val(id);
                        
                        $("#tornar-documento-obsoleto-modal").modal({
                            backdrop: 'static',
                            keyboard: false
                        });
                    });

                });
            </script>

        </div>
        <!-- ============================================================== -->
        <!-- End Container fluid -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Page wrapper -->
    <!-- ============================================================== -->


    <!-- Formulário para visualizar o formulário ( dafuk ?????? )  -->
    {{ Form::open(['route' => 'formularios.view-formulario', 'target'=>'_blank', 'id'=>'form-view-formulario', 'method' => 'POST']) }}
        {{ Form::hidden('action', 'view') }}
    {{ Form::close() }}


@endsection


@section('footer')
    <script src="{{ asset('plugins/multiselect/js/jquery.multi-select.js') }}"></script>
    <script src="{{ asset('plugins/quicksearch/jquery.quicksearch.js') }}"></script>
    
    <!-- This is data table -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    
    <!-- start - This is for export functionality only -->
    <script src="{{ asset('js/dataTables/dataTables-1.2.2.buttons.min.js') }}"></script>
    <script src="{{ asset('js/dataTables/buttons-1.2.2.flash.min.js') }}"></script>
    <script src="{{ asset('js/dataTables/jszip-2.5.0.min.js') }}"></script>
    <script src="{{ asset('js/dataTables/pdfmake-0.1.18.min.js') }}"></script>
    <script src="{{ asset('js/dataTables/vfs_fonts-0.1.18.js') }}"></script>
    <script src="{{ asset('js/dataTables/buttons-1.2.2.html5.min.js') }}"></script>
    <script src="{{ asset('js/dataTables/buttons-1.2.2.print.min.js') }}"></script>
    <!-- end - This is for export functionality only -->

    <script>
        
        function viewFormulario(id){
            $("#form-view-formulario").append("<input type='hidden' name='formulario_id' value="+id+" >");
            $("#form-view-formulario").submit();
        }
        
        $(function(){
            /*
            * 
            * MultiSelect de NOVA ÁREA DE INTERESSE
            *
            */
            $('#optgroup-newAreaDeInteresse').multiSelect({
                selectableOptgroup: true,
                selectableHeader: "<input type='text' class='form-control search-input' autocomplete='off' placeholder='Pesquisar usuários'>",
                selectionHeader: "<input type='text' class='form-control search-input' autocomplete='off' placeholder='Pesquisar usuários'>",
                afterInit: function(ms){
                    var that = this,
                        $selectableSearch = that.$selectableUl.prev(),
                        $selectionSearch = that.$selectionUl.prev(),
                        selectableSearchString = '#'+that.$container.attr('id')+' .ms-elem-selectable:not(.ms-selected)',
                        selectionSearchString = '#'+that.$container.attr('id')+' .ms-elem-selection.ms-selected';

                    that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
                    .on('keydown', function(e){
                    if (e.which === 40){
                        that.$selectableUl.focus();
                        return false;
                    }
                    });

                    that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
                    .on('keydown', function(e){
                    if (e.which == 40){
                        that.$selectionUl.focus();
                        return false;
                    }
                    });
                },
                afterSelect: function(){
                    this.qs1.cache();
                    this.qs2.cache();
                },
                afterDeselect: function(values){
                    this.qs1.cache();
                    this.qs2.cache();
                }
            });


            /*
            * MultiSelect de NOVO GRUPO DE TREINAMENTO PARA O DOCUMENTO
            */
            $('#optgroup-newGrupoTreinamentoDoc').multiSelect({
                selectableOptgroup: true,
                selectableHeader: "<input type='text' class='form-control search-input' autocomplete='off' placeholder='Pesquisar usuários'>",
                selectionHeader: "<input type='text' class='form-control search-input' autocomplete='off' placeholder='Pesquisar usuários'>",
                afterInit: function(ms){
                    var that = this,
                        $selectableSearch = that.$selectableUl.prev(),
                        $selectionSearch = that.$selectionUl.prev(),
                        selectableSearchString = '#'+that.$container.attr('id')+' .ms-elem-selectable:not(.ms-selected)',
                        selectionSearchString = '#'+that.$container.attr('id')+' .ms-elem-selection.ms-selected';

                    that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
                    .on('keydown', function(e){
                    if (e.which === 40){
                        that.$selectableUl.focus();
                        return false;
                    }
                    });

                    that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
                    .on('keydown', function(e){
                    if (e.which == 40){
                        that.$selectionUl.focus();
                        return false;
                    }
                    });
                },
                afterSelect: function(){
                    this.qs1.cache();
                    this.qs2.cache();
                },
                afterDeselect: function(values){
                    this.qs1.cache();
                    this.qs2.cache();
                }
            });


            /*
            * MultiSelect de NOVO GRUPO DE DIVULGAÇÃO PARA O DOCUMENTO
            */
            $('#optgroup-newGrupoDivulgacaoDoc').multiSelect({
                selectableOptgroup: true,
                selectableHeader: "<input type='text' class='form-control search-input' autocomplete='off' placeholder='Pesquisar usuários'>",
                selectionHeader: "<input type='text' class='form-control search-input' autocomplete='off' placeholder='Pesquisar usuários'>",
                afterInit: function(ms){
                    var that = this,
                        $selectableSearch = that.$selectableUl.prev(),
                        $selectionSearch = that.$selectionUl.prev(),
                        selectableSearchString = '#'+that.$container.attr('id')+' .ms-elem-selectable:not(.ms-selected)',
                        selectionSearchString = '#'+that.$container.attr('id')+' .ms-elem-selection.ms-selected';

                    that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
                    .on('keydown', function(e){
                    if (e.which === 40){
                        that.$selectableUl.focus();
                        return false;
                    }
                    });

                    that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
                    .on('keydown', function(e){
                    if (e.which == 40){
                        that.$selectionUl.focus();
                        return false;
                    }
                    });
                },
                afterSelect: function(){
                    this.qs1.cache();
                    this.qs2.cache();
                },
                afterDeselect: function(values){
                    this.qs1.cache();
                    this.qs2.cache();
                }
            });


            $("[data-target='#vinculos-form-modal']").click(function(){
                var forms = JSON.parse($(this).attr('data-forms'));
                var id = $(this).attr('data-id');
                var status = $(this).attr('data-finalizado');

                $("#save-link-form").append("<input type='hidden' name='documento_id' value='"+id+"' >")
                var select = $(".select2-vinculos").select2({
                    templateSelection: function (d) { 
                        return $('<a href="#" onclick="viewFormulario('+d.id+')" ><b>'+d.text+'</b></a>'); 
                    },
                });
                select.val(forms);
                select.trigger('change');   
            });
            
            $(".btn-save-link").click(function(){
                $("#save-link-form").submit();
            });

            /*
            * DATA-TABLE
            */
            $(document).ready(function() {
                $('#example23').DataTable({
                    "language": {
                        "sEmptyTable": "Nenhum registro encontrado",
                        "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                        "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
                        "sInfoFiltered": "(Filtrados de _MAX_ registros)",
                        "sInfoPostFix": "",
                        "sInfoThousands": ".",
                        "sLengthMenu": "_MENU_ resultados por página",
                        "sLoadingRecords": "Carregando...",
                        "sProcessing": "Processando...",
                        "sZeroRecords": "Nenhum registro encontrado",
                        "sSearch": "Pesquisar",
                        "oPaginate": {
                            "sNext": "Próximo",
                            "sPrevious": "Anterior",
                            "sFirst": "Primeiro",
                            "sLast": "Último"
                        },
                        "oAria": {
                            "sSortAscending": ": Ordenar colunas de forma ascendente",
                            "sSortDescending": ": Ordenar colunas de forma descendente"
                        }
                    },
                    dom: 'Bfrtip',
                    buttons: [
                        { extend: 'excel',  text: 'Excel' },
                        { extend: 'pdf',    text: 'PDF' },
                        { extend: 'print',  text: 'Imprimir' }
                    ]
                });

                $(document).ready(function() {
                    var table = $('#example').DataTable({
                        "columnDefs": [{
                            "visible": false,
                            "targets": 2
                        }],
                        "order": [
                            [2, 'asc']
                        ],
                        "displayLength": 25,
                        "drawCallback": function(settings) {
                            var api = this.api();
                            var rows = api.rows({
                                page: 'current'
                            }).nodes();
                            var last = null;
                            api.column(2, {
                                page: 'current'
                            }).data().each(function(group, i) {
                                if (last !== group) {
                                    $(rows).eq(i).before('<tr class="group"><td colspan="5">' + group + '</td></tr>');
                                    last = group;
                                }
                            });
                        }
                    });
                    // Order by the grouping
                    $('#example tbody').on('click', 'tr.group', function() {
                        var currentOrder = table.order()[0];
                        if (currentOrder[0] === 2 && currentOrder[1] === 'asc') {
                            table.order([2, 'desc']).draw();
                        } else {
                            table.order([2, 'asc']).draw();
                        }
                    });
                });

                $(document).on("click", ".submit-view-documento", (elm) => {
                    let id = $(elm.target).attr('data-id');
                    $(`#view-documento-${id}`)[0].submit();
                });
            });
            

        });
    </script>
@endsection