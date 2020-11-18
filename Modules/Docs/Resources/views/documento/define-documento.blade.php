@extends('core::layouts.app')

@extends('core::layouts.menuDocs')
@yield('menu')

@section('content')
    <!-- O que fazer nestas situações? -->
    
    <!-- jQuery -->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('plugins/dropify/dist/css/dropify.min.css') }}">

    <script>
        function openNav() {
            document.getElementById("div-overlay-define-documento").style.width = "100%";
        }

        function closeNav() {
            document.getElementById("div-overlay-define-documento").style.width = "0%";
            window.location.href = " {{ URL::route('documentacao') }} ";
        }
    </script>

@if (isset($overlay_sucesso))
    <script>
        openNav();
        $("#message-overlay").text('DOCUMENTO ENCAMINHADO AO SETOR DE PROCESSOS PARA AVALIAÇÃO');
        $("#icon-overlay").removeClass('fa-send').addClass('fa-check');
    </script>
@else

    <!-- Page wrapper  -->
    <!-- ============================================================== -->
    <div class="page-wrapper" >
        <!-- ============================================================== -->
        <!-- Container fluid  -->
        <!-- ============================================================== -->
        <div class="container-fluid">
            

            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <div class="row page-titles">
                <div class="col-md-5 col-8 align-self-center">
                    <h3 class="text-themecolor m-b-0 m-t-0">Definição de Documento</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ URL::route('home') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ URL::route('documentacao') }}">Documentação</a></li>
                        <li class="breadcrumb-item active">Definição de Documento</li>
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
            
            <!-- Informações do Novo Documento -->
            <div class="row">
                <div class="col-12">

                    <!-- Card -->
                    <div class="card text-center">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="pull-left"><b>TÍTULO DO DOCUMENTO: </b>{{ $tituloDocumento }}  </p>
                                </div>
                                <div class="col-md-6 form-material">
                                    <div class="row">
                                        <label for="codigoDocumento" class="col-3" style="margin-left: -2%;"><b>CÓDIGO: </b></label>
                                        <div class="col-6" style="margin-left: -6%; margin-top: -2%;">
                                            <input class="form-control" type="text" value="{{ $codigoDocumento }}" id="codigoDocumento">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="pull-left"><b>NÍVEL DE ACESSO: </b>{{ $nivelAcessoDocumento }}  </p>
                                </div>
                                <div class="col-md-6">
                                    <p class="pull-left"><b>VALIDADE DO DOCUMENTO: </b>{{ $validadeDocumento }}  </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="pull-left"><b>SETOR: </b>{{ $text_setorDono }}  </p>
                                </div>
                                <div class="col-md-6">
                                    <p class="pull-left"><b>APROVADOR: </b>{{ $text_aprovador }}  </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="pull-left"><b>CÓPIA CONTROLADA: </b>{{ $text_copiaControlada }}  </p>
                                </div>
                                <div class="col-md-6">
                                    <p class="pull-left"><b>TIPO DE DOCUMENTO: </b>{{ $text_tipo_documento }}  </p>
                                </div>
                            </div>

                            @if( count($formsAtrelados) > 0 )
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="pull-left"><b>FORMULÁRIOS ATRELADOS: </b>{{ $text_formsAtrelados }}  </p>
                                    </div>   
                                </div>
                            @endif

                        </div>
                        <div class="card-footer text-muted">
                            Informações do Novo Documento
                        </div>
                    </div>
                    <!-- Card -->
                    
                </div>
            </div>
            

            <!-- Ação Principal da Página -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            @if($acao == "import")
                                <div class="row">
                                    <div class="col-lg-12 col-md-12">
                                        
                                        <div class="col-md-12 mb-4">
                                            @if ($errors->any())
                                                <div class="alert alert-danger">
                                                    <ul>
                                                        @foreach ($errors->all() as $error)
                                                            <li>{{ $error }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                        </div>

                                        
                                        {!! Form::open(['route' => 'ajax.documentos.saveAttachedDocument', 'method' => 'POST', 'id' => 'form-upload-document', 'enctype' => 'multipart/form-data']) !!}
                                            {{ csrf_field() }}

                                            <div class="card">
                                                <div class="card-body">
                                                    <h4 class="card-title"> Upload de documentos </h4>
                                                    <label for="input-file-now">Por favor, anexe o arquivo que você deseja controlar dentro do sistema.</label>
                                                    {!! Form::file('doc_uploaded', ['class' => 'dropify', 'id' => 'input-file-now', 'required' => 'required']) !!}
                                                    
                                                    {!! Form::hidden('tipo_documento',              $tipo_documento) !!}
                                                    {!! Form::hidden('nivel_acesso',                $nivelAcessoDocumento) !!}
                                                    {!! Form::hidden('id_aprovador',                $aprovador) !!}
                                                    {!! Form::hidden('setor_dono_doc',              $setorDono) !!}
                                                    {!! Form::hidden('copiaControlada',             $copiaControlada) !!}                                                
                                                    {!! Form::hidden('tituloDocumento',             $tituloDocumento) !!}
                                                    {!! Form::hidden('codigoDocumento',             $codigoDocumento) !!}
                                                    {!! Form::hidden('validadeDocumento',           $validadeDocumento) !!}

                                                    @if( count($areaInteresse) > 0 )
                                                        @foreach($areaInteresse as $usuariosInteresse)
                                                            <input type="hidden" name="areaInteresse[]" value="<?php echo $usuariosInteresse ?>">
                                                        @endforeach
                                                    @endif

                                                    @if( count($grupoTreinamento) > 0 )
                                                        @foreach($grupoTreinamento as $usuariosTreinamento)
                                                            <input type="hidden" name="grupoTreinamentoDocumento[]" value="<?php echo $usuariosTreinamento ?>">
                                                        @endforeach
                                                    @endif

                                                    @if( count($grupoDivulgacao) > 0 )
                                                        @foreach($grupoDivulgacao as $usuariosDivulgacao)
                                                            <input type="hidden" name="grupoDivulgacaoDocumento[]" value="<?php echo $usuariosDivulgacao ?>">
                                                        @endforeach
                                                    @endif

                                                    @if( count($formsAtrelados) > 0 )
                                                        @foreach($formsAtrelados as $formAtrelado)
                                                            <input type="hidden" name="formsAtrelados[]" value="<?php echo $formAtrelado ?>">
                                                        @endforeach
                                                    @endif

                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12">
                                                <div class="col-md-offset-2 col-md-3 pull-right">
                                                    {!! Form::submit('Salvar Documento', ['class' => 'btn btn-lg btn-success', 'id' => 'btn-save-attached-document']) !!}
                                                </div>
                                                <div class="col-md-offset-2 col-md-3 pull-right">
                                                    <button type="button" class="btn waves-effect waves-light btn-block btn-lg btn-secondary" onclick="history.back()">Voltar</button>
                                                </div>
                                            </div>

                                        {!! Form::close() !!}

                                    </div>
                                </div>
                            @else
                                <h3>Novo Documento:</h3>

                                <!-- Editor -->
                                <div class="container">
                                    <iframe width="100%" id="speed-onlyoffice-editor" src="{{ asset('plugins/onlyoffice-php/doceditor.php?&user=&fileID=').$docPath }}"> </iframe>
                                </div>
                                <!-- End Editor -->
                                        
                                {!! Form::open(['route' => 'ajax.documentos.saveNewDocument', 'method' => 'POST', 'id' => 'form-upload-new-document', 'enctype' => 'multipart/form-data']) !!}
                                    {{ csrf_field() }}
                                
                                    {!! Form::hidden('tipo_documento',      $tipo_documento) !!}
                                    {!! Form::hidden('nivel_acesso',        $nivelAcessoDocumento) !!}
                                    {!! Form::hidden('id_aprovador',        $aprovador) !!}
                                    {!! Form::hidden('setor_dono_doc',      $setorDono) !!}
                                    {!! Form::hidden('copiaControlada',     $copiaControlada) !!}                                                
                                    {!! Form::hidden('tituloDocumento',     $tituloDocumento) !!}
                                    {!! Form::hidden('codigoDocumento',     $codigoDocumento) !!}
                                    {!! Form::hidden('validadeDocumento',   $validadeDocumento) !!}

                                    @if( count($areaInteresse) > 0 )
                                        @foreach($areaInteresse as $usuariosInteresse)
                                            <input type="hidden" name="areaInteresse[]" value="<?php echo $usuariosInteresse ?>">
                                        @endforeach
                                    @endif

                                    @if( count($grupoTreinamento) > 0 )
                                        @foreach($grupoTreinamento as $usuariosTreinamento)
                                            <input type="hidden" name="grupoTreinamentoDocumento[]" value="<?php echo $usuariosTreinamento ?>">
                                        @endforeach
                                    @endif

                                    @if( count($grupoDivulgacao) > 0 )
                                        @foreach($grupoDivulgacao as $usuariosDivulgacao)
                                            <input type="hidden" name="grupoDivulgacaoDocumento[]" value="<?php echo $usuariosDivulgacao ?>">
                                        @endforeach
                                    @endif

                                    @if( count($formsAtrelados) > 0 )
                                        @foreach($formsAtrelados as $formAtrelado)
                                            <input type="hidden" name="formsAtrelados[]" value="<?php echo $formAtrelado ?>">
                                        @endforeach
                                    @endif
                                {!! Form::close() !!}
        
                                <div class="col-lg-12 col-md-12">
                                    <br>
                                    <div class="col-md-offset-2 col-md-3 pull-right">
                                        <input type="button" id="btn-save-new-document" class="btn btn-lg btn-success" value="Salvar Documento">
                                    </div>
                                    <div class="col-md-offset-2 col-md-3 pull-right">
                                        <button type="button" class="btn waves-effect waves-light btn-block btn-lg btn-secondary" onclick="history.back()">Voltar</button>
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- End Page Content -->
            <!-- ============================================================== -->

            <!-- Modal de Anexos -->
            <div id="modal-anexos" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="overflow-y: auto">
                <div class="modal-dialog modal-lg">
                <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="mySmallModalLabel">Você deseja colocar anexos ao documento <b>{{ $tituloDocumento }} ? </b> </h4>
                            <button type="button" id="btn-lista-anexos" class="btn btn-primary btn-circle" data-toggle="collapse" data-target="#lista-anexos-cadastrados" aria-expanded="false" aria-controls="lista-anexos-cadastrados" role="tab" style="cursor: pointer"><i class="fa fa-list" data-toggle="tooltip" data-original-title="Listar Anexos Cadastrados"  aria-hidden="true"></i></button>
                        </div>
                        
                        <div class="modal-body"> 
                            <div class="row">
                                <div class="col-md-12">
                                    <h6 class="alert alert-info alert-dismissible" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        Ações envolvendo anexos podem demorar um pouco. Após executar alguma ação, por favor, aguarde a mensagem de sucesso!
                                    </h6>
                                </div>
                                <div class="col-md-12">
                                    <div class="collapse" id="lista-anexos-cadastrados" role="tabpanel">
                                        <h3>Listagem de Anexos do Documento</h3>
                                        <div class="table-responsive">
                                            <table class="table table-condensed">
                                                <thead>
                                                    <tr>
                                                        <th class="text-nowrap text-center">Título do Anexo</th>
                                                        <th class="text-nowrap text-center">Data de Inserção</th>
                                                        <th class="text-nowrap text-center">Remover</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="attachment-table-body">
                                                    
                                                </tbody>
                                            </table>
                                        </div>
                                        <hr style="border-top: 2px solid darkgray">
                                    </div>
                                </div>
                                
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="card-title"> Upload de anexo </h4>
                                            {!! Form::open(['route' => 'ajax.anexos.save', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'form-save-attachment']) !!}
                                                <label for="input-file-now">Por favor, selecione o arquivo que você deseja anexar ao documento atual.</label>
                                                {!! Form::file('anexo_escolhido[]', ['class' => 'dropify', 'id' => 'anexo_escolhido', 'multiple' => 'multiple', 'data-allowed-file-extensions'=>'pdf doc docx xlsx xls png jpg jpeg tif']) !!}

                                                <div class="col-md-12 mt-3">
                                                    <div class="col-md-9 pull-left">
                                                        {!! Form::hidden('document_id', null, ['id' => 'doc_link_attached']) !!}
                                                    </div>
                                                    <div class="col-md-3 pull-right">
                                                        {!! Form::submit('Salvar Anexo', ['class' => 'btn btn-lg btn-success', 'id' => 'btn-save-attachment']) !!}
                                                    </div>
                                                </div>
                                            {!! Form::close() !!}
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="modal-footer">
                            {!! Form::open(['route' => 'documentacao.save-attached-start-workflow', 'method' => 'POST']) !!}
                                {!! Form::hidden('documento_id', null, ['id' => 'doc_link_attached3']) !!}
                                @if( isset($acao) ) 
                                    {!! Form::hidden('acao', $acao) !!}
                                @endif
                                <button type="submit" class="btn btn-lg btn-secundary waves-effect" id="end-and-send">Concluir e Enviar ao setor Processos</button>
                            {!! Form::close() !!}
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
            </div>
            <!-- /.Modal de Anexos -->


             <!-- Modal de confirmação - deseja mesmo excluir o anexo -->
             <div class="modal fade" id="confirm-delete-attachment" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" style="display: none;">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-body"> 
                            Deseja realmente excluir o anexo selecionado ? 
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Não</button>

                            {{ Form::open(['route' => 'ajax.anexos.removeAttachment', 'method' => 'POST', 'id' => 'delete-attachment']) }}
                                {{ Form::hidden('documento_id', null, ['id' => 'doc_link_attached2']) }}
                                {{ Form::hidden('anexo_id', null, ['id' => 'hidden_anexo_id_to_remove']) }}
                                <button type="submit" id="btn-confirm-delete-attachment" class="btn btn-success waves-effect"> Sim </button>
                            {{ Form::close() }}
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.Modal de confirmação - deseja mesmo excluir o anexo -->




        </div>
        <!-- ============================================================== -->
        <!-- End Container fluid -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Page wrapper -->
    <!-- ============================================================== -->


@endif


@endsection


@section('footer')
    <script>

        $("#anexo_escolhido").dropify({
            tpl: {
                filename: '<p class="dropify-filename"><span class="file-icon"></span> <span class="dropify-filename-inner-multiple"></span></p>',
            }
        });

        $(document).ready(function(){

            var main = "{{ isset($overlay_sucesso) ? true : false }}";

            if(!main) {

                //Iniciando Speed Editor
                var acao = "{{ isset($acao) ? $acao : ''}}";

                // [CRIAÇÃO] Ao clicar para salvar documento que foi criado na ferramenta (salva doc e retorna para salvar anexo)
                $("#btn-save-new-document").click(function(){
                    
                    if( $("#codigoDocumento").val() == null  ||  $("#codigoDocumento").val() == "" ) {
                        showToast('Opa!', 'Você precisa fornecer um código para que o documento seja salvo.', 'error');
                        return;
                    }

                    var codigoDocumento = $("#codigoDocumento").val();
                    var form = $("#form-upload-new-document");
                    form.find("input[name=codigoDocumento]").val(codigoDocumento);
                    var formData = new FormData( $("#form-upload-new-document")[0] );
                    var url = form.attr('action');

                    let obj = {'codigo': codigoDocumento};
                    ajaxMethod('POST', " {{ URL::route('ajax.documentos.checkIfDocumentCodeExists') }} ", obj).then(function(result) {
                        if(result.exist) {
                            showToast('Opa!', 'Já existe um documento utilizando esse código!', 'error');
                            return;
                        }
                    
                        $.ajax({  
                            type: "POST",  
                            url: url,  
                            data: formData,
                            async: false,
                            cache: false,
                            contentType: false,
                            processData: false,
                            success: function(data) {
                                $("#modal-anexos").modal({ backdrop: 'static', keyboard: false});
                                $("#doc_link_attached").val(data.response);
                                $("#doc_link_attached2").val(data.response);
                                $("#doc_link_attached3").val(data.response);
                                $("#btn-lista-anexos").attr('data-document-id', data.response);
                            }
                        });
                    }); 
                });


                // [UPLOAD] Ao clicar para salvar documento upload (salva doc e retorna para salvar anexo)
                $("#form-upload-document").submit(function(e){
                    e.preventDefault();

                    if( $("#input-file-now").val() == null  ||  $("#input-file-now").val() == "" ) {
                        showToast('Opa!', 'Você precisa escolher um arquivo.', 'error');
                        return;
                    }
                    if( $("#codigoDocumento").val() == null  ||  $("#codigoDocumento").val() == "" ) {
                        showToast('Opa!', 'Você precisa fornecer um código para que o documento seja salvo.', 'error');
                        return;
                    }

                    var codigoDocumento = $("#codigoDocumento").val();
                    var form = $(this);
                    form.find("input[name=codigoDocumento]").val(codigoDocumento);
                    var formData = new FormData($(this)[0]);
                    var url = form.attr('action');

                    let obj = {'codigo': codigoDocumento};
                    ajaxMethod('POST', " {{ URL::route('ajax.documentos.checkIfDocumentCodeExists') }} ", obj).then(function(result) {
                        if(result.exist) {
                            showToast('Opa!', 'Já existe um documento utilizando esse código!', 'error');
                            return;
                        }

                        $.ajax({  
                            type: "POST",  
                            url: url,  
                            data: formData,
                            async: false,
                            cache: false,
                            contentType: false,
                            processData: false,
                            success: function(data) {
                                $("#modal-anexos").modal({ backdrop: 'static', keyboard: false});
                                $("#doc_link_attached").val(data.response);
                                $("#doc_link_attached2").val(data.response);
                                $("#doc_link_attached3").val(data.response);
                                $("#btn-lista-anexos").attr('data-document-id', data.response);
                            }
                        });
                    }); 
                });

                // Coloca o nome original do arquivo no campo do nome do anexo

                $(document).on("change", "#anexo_escolhido", function(e){
                    var files = $("#anexo_escolhido")[0].files;
                    $.each(files, function (i, el) {
                        $(".dropify-filename-inner-multiple").text($(".dropify-filename-inner-multiple").text() + "   " + el.name);
                    });
                });

                // Função para salvar anexo
                $("#form-save-attachment").submit(function(e){
                    e.preventDefault();
                    if( $("#anexo_escolhido").val() == null  ||  $("#anexo_escolhido").val() == "" ) {
                        showToast('Opa!', 'Você precisa escolher um arquivo.', 'error');
                        return;
                    }        

                    var form = $(this);
                    var formData = new FormData($(this)[0]);
                    var url = form.attr('action');
                    $.ajax({  
                        type: "POST",  
                        url: url,  
                        data: formData,
                        async: false,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function(data) {
                            showToast('Sucesso!', 'O anexo foi salvo.', 'success');
                            
                            // Limpa Valores
                            $("#anexo_escolhido").val('');
                            $(".dropify-clear").trigger('click');
                            $("#nome_anexo").val('');

                            // Atualiza lista dos anexos já cadastrados
                            $("#btn-lista-anexos").trigger('click');
                        }
                    }); 
                });

                // Toda vez que a listagem de anexos do documento for aberta
                $('#btn-lista-anexos').click(function () {
                    var idDoc = $(this).attr('data-document-id');

                    var obj = {'document_id': idDoc};        
                    ajaxMethod('POST', " {{ URL::route('ajax.anexos.getAnexos') }} ", obj).then(function(result) {
                        $("#attachment-table-body").empty();
                        var data = result.response;
                        data.forEach(function(key) {
                            let arrayImag = ["png", "jpg", "jpeg", "tif", "gif"];
                            var event = new Date(key.created_at);
                            var year = event.getFullYear(), month = event.getMonth()+1, date1 = event.getDate(), hour = event.getHours(), minutes = event.getMinutes();
                            var dateF = hour +":"+ minutes +"  "+ date1 +"/"+ month +"/" + year;

                            console.log(key);
                            var tr = '<tr>';
                            tr += '<td class="text-nowrap text-center">';
                            
                            if (arrayImag.indexOf(key.extensao) >= 0) {
                                tr += '<a href="{{ asset("plugins/onlyoffice-php/Storage/anexos")}}/' + key.hash + '.' + key.extensao + '" target="_blank">'+ key.nome +'</a>';
                            } else {
                                tr += '<a href="{{ asset("plugins/onlyoffice-php/doceditor.php?p=1&folder=anexos&fileID=") }}'+key.encodeFilePath+' " target="_blank">'+ key.nome +'</a>';
                            }

                            tr += '</td><td class="text-nowrap text-center">'+ dateF +'</td><td class="text-nowrap text-center"><button type="button" id="btn-delete-attachment-modal" class="btn btn-rounded btn-danger" data-anexo-id="'+ key.id +'"> <i class="fa fa-close"></i> </button></td>'; 
                            tr += '</tr>';
                            $("#attachment-table-body").append(tr);
                        });
                    }, function(err) {
                        console.log(err);
                    });
                })

                // Quando o botão para remover anexo é clicado, abre modal de confirmação
                $(document).on("click", "#btn-delete-attachment-modal", function(){
                    var anexo_id = $(this).data("anexo-id");
                    $("#hidden_anexo_id_to_remove").val(anexo_id);

                    $("#confirm-delete-attachment").modal({ backdrop: false, keyboard: false});
                });

                // Se o usuário confirmar que deseja, realmente, excluir o anexo, invoca Ajax
                $("#delete-attachment").submit(function(e){
                    e.preventDefault();

                    var form = $(this);
                    var url = form.attr('action');
                    ajaxMethod('POST', url, form.serialize()).then(function(result) {
                        showToast('Sucesso!', 'O anexo foi removido.', 'success');
                        $("#confirm-delete-attachment").modal('toggle');
                        $("#btn-lista-anexos").trigger('click');
                    }, function(err) {
                    });
                });
            
            }
            
        });


    </script>
@endsection