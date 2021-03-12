<div class="modal" id="modal-anexos" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="mySmallModalLabel">Você deseja colocar anexos ao documento <b id="nomeDocumento"></b> </h4>
                <button type="button" id="btn-lista-anexos" class="btn btn-primary btn-circle ml-3" data-toggle="collapse" data-target="#lista-anexos-cadastrados" aria-expanded="false" aria-controls="lista-anexos-cadastrados" role="tab" style="cursor: pointer"><i class="fa fa-list" data-toggle="tooltip" data-original-title="Listar Anexos Cadastrados"  aria-hidden="true"></i></button>
                @if ($comportamento_modal != 'CRIACAO')
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                @endif
            </div>
            
            <div class="modal-body"> 
                <div class="row">
                    <div class="col-md-12">
                        <h6 class="alert alert-info alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            Ações envolvendo anexos podem demorar um pouco. Após executar alguma ação, por favor, aguarde a mensagem de sucesso!
                        </h6>
                    </div>
                    @if(Session::has('message'))
                        @component('components.alert')@endcomponent
            
                        {{ Session::forget('message') }}
                    @endif
                    <div class="col-md-12">
                        <div class="collapse" id="lista-anexos-cadastrados" role="tabpanel">
                            <h3>Listagem de Anexos do Documento</h3>
                            <div class="table-responsive">
                                <table class="table table-condensed">
                                    <thead>
                                        <tr>
                                            <th class="text-nowrap text-center">Título do Anexo</th>
                                            <th class="text-nowrap text-center">Data de Inserção</th>
                                            <th class="text-nowrap text-center">Controle</th>
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
                                {!! Form::open(['route' => 'docs.anexo.salvar', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'id' => 'form-save-attachment']) !!}
                                {{ csrf_field() }}
                                    <label for="input-file-now">Por favor, selecione o arquivo que você deseja anexar ao documento atual.</label>
                                    {!! Form::file('anexo_escolhido[]', ['class' => 'dropify', 'id' => 'anexo_escolhido', 'multiple'=>'multiple', 'data-allowed-file-extensions'=>'pdf doc docx xlsx xls png jpg jpeg tif', 'required' => 'true']) !!}
            
                                    <div class="col-md-12 mt-3">
                                        <div class="col-md-9 pull-left">
                                            <input type="hidden" name="idDocumento" id="idDocumento" value="{{$idDocumento}}">
                                        </div>
                                        <div class="col-md-1 pull-right">
                                            <button type="submit"  class="btn btn-success">@lang('buttons.general.save')</button>
                                        </div>
                                    </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                    @if ($comportamento_modal == 'CRIACAO')
                        <div class="col-md-12 mt-3">
                            <div class="col-md-1">
                                <a href="{{route('docs.documento.proxima-etapa')}}" type="button"  class="btn btn-info">@lang('buttons.general.next')</a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            
        </div>
    </div>
</div>
<link href="{{ asset('plugins/jquery-loading/jquery.loading.min.css') }}" rel="stylesheet">
<script src="{{ asset('plugins/jquery-loading/jquery.loading.min.js') }}"></script>
<script>
    $(document).ready(function(){
        //Busca Anexos documentos
        buscaAnexo();
        
        // Basic
        $('.dropify').dropify();

        // Translated
        $('.dropify-fr').dropify({
            messages: {
                default: 'Glissez-déposez un fichier ici ou cliquez',
                replace: 'Glissez-déposez un fichier ou cliquez pour remplacer',
                remove: 'Supprimer',
                error: 'Désolé, le fichier trop volumineux'
            }
        });

        // Used events
        var drEvent = $('#input-file-events').dropify();

        drEvent.on('dropify.beforeClear', function(event, element) {
            return confirm("Do you really want to delete \"" + element.file.name + "\" ?");
        });

        drEvent.on('dropify.afterClear', function(event, element) {
            alert('File deleted');
        });

        drEvent.on('dropify.errors', function(event, element) {
            console.log('Has Errors');
        });

        var drDestroy = $('#input-file-to-destroy').dropify();
        drDestroy = drDestroy.data('dropify')
        $('#toggleDropify').on('click', function(e) {
            e.preventDefault();
            if (drDestroy.isDropified()) {
                drDestroy.destroy();
            } else {
                drDestroy.init();
            }
        })

        $(document).on("click","#btn-lista-anexos", function() {
            buscaAnexo();
        });

        // Exclusão
        $(document).on("click", "#btn-delete-attachment-modal", function() {
            let id = $(this).data('anexo-id');
            let deleteIt = swal2_warning("Essa ação é irreversível!");
            let obj = {'id': id};

            deleteIt.then(resolvedValue => {
                ajaxMethod('POST', "{{ URL::route('docs.anexo.deletar') }}", obj).then(response => {
                    if(response.response != 'erro') {
                        swal2_success_not_reload("Excluído!", "Anexo excluído com sucesso.");
                        $("#btn-lista-anexos").trigger('click');
                    } else {
                        swal2_alert_error_not_reload("Tivemos um problema ao excluir o anexo.");
                    }
                }, error => {
                    console.log(error);
                });
            }, error => {
                swal.close();
            });
        });

        //Visualizacao
        $(document).on("click", "#btn-view-attachment-modal", function() {
            $('#modal-anexos').loading({
              stoppable: true,
              message: "Carregando...",
              theme: "dark"
            });
            let id = $(this).data('anexo-id');
            let obj = {'id': id};

            ajaxMethod('POST', "{{ URL::route('docs.anexo.busca-anexo-ged') }}", obj).then(ret => {
                if(ret.response == 'erro') {
                    swal2_alert_error_support("Tivemos um problema ao buscar o anexo no GED.");
                }
                window.open(ret.data.caminho, '_blank');
                $('#modal-anexos').loading('stop');
            }, error => {
                console.log(error);
                $('#modal-anexos').loading('stop');
            });
        });
        

        $("#form-save-attachment").submit(function(e){
            e.preventDefault();
            $('#lista-anexos-cadastrados').attr('class', 'collapse');
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
                },
                error: function(erro){
                    console.log(erro);
                }
            }); 
        });

    });

    function buscaAnexo()
    {
        
        

        let id  = $('#idDocumento').val();
        let obj = {'id': id};
        ajaxMethod('POST', "{{ URL::route('docs.anexo') }}", obj).then(ret => {
            if(ret.response == 'erro') {
                swal2_alert_error_support("Tivemos um problema ao buscar o anexo.");
            }
            let dados = ret.data;
            let linha = '';
            $('#attachment-table-body').empty();
            for (let index = 0; index < dados.length; index++) {
                const element = dados[index];

                linha += '<tr><td class="text-nowrap text-center">'+element.nome+'</td>';
                linha += '<td class="text-nowrap text-center">'+moment(element.created_at).format('DD/MM/YYYY')+'</td>';
                linha += '<td class="text-nowrap text-center">';
                linha += '<a href="#" class="btn waves-effect waves-light btn-danger sa-warning mr-1" id="btn-delete-attachment-modal" data-anexo-id="'+element.id+'"> <i class="mdi mdi-delete"></i> Excluir </a>';
                linha += '<a href="#"  class="btn waves-effect waves-light btn-info" id="btn-view-attachment-modal" data-anexo-id="'+element.id+'"> <i class="mdi mdi-eye"></i> Visualizar </a>';
                linha += '</td>';
                linha += '</tr>';
            }
            $('#attachment-table-body').append(linha); 
        }, error => {
            console.log(error);
        });
        
    }

</script>