<div class="modal-header">
    <h4 class="modal-title" id="mySmallModalLabel">Você deseja colocar anexos ao documento <b>{{ $tituloDocumento }} ? </b> </h4>
    <button type="button" id="btn-lista-anexos" data-document-id="{{$id}}" class="btn btn-primary btn-circle" data-toggle="collapse" data-target="#lista-anexos-cadastrados" aria-expanded="false" aria-controls="lista-anexos-cadastrados" role="tab" style="cursor: pointer"><i class="fa fa-list" data-toggle="tooltip" data-original-title="Listar Anexos Cadastrados"  aria-hidden="true"></i></button>
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
                                <th class="text-nowrap text-center">Remover</th>
                            </tr>
                        </thead>
                        <tbody id="attachment-table-body">
                            @foreach ($anexos as $anexo)
                            <tr>
                                <td class="text-nowrap text-center">{{$anexo->nome}}</td>
                                <td class="text-nowrap text-center">{{date('d/m/Y H:i:s', strtotime($anexo->created_at))}}</td>
                                <td class="text-nowrap text-center"><button type="button" id="btn-delete-attachment-modal" class="btn btn-rounded btn-danger" data-anexo-id="{{$anexo->id}}"> <i class="fa fa-close"></i> </button></td>
                            </tr>
                            @endforeach
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
                        <label for="input-file-now">Por favor, selecione o arquivo que você deseja anexar ao documento atual.</label>
                        {!! Form::file('anexo_escolhido[]', ['class' => 'dropify', 'id' => 'anexo_escolhido', 'multiple' => 'multiple', 'data-allowed-file-extensions'=>'pdf doc docx xlsx xls png jpg jpeg tif', 'required' => 'true']) !!}

                        <div class="col-md-12 mt-3">
                            <div class="col-md-9 pull-left">
                                {!! Form::hidden('idDocumento', $id, ['id' => 'idDocumento']) !!}
                            </div>
                            <div class="col-md-3 pull-right">
                                {!! Form::submit('Salvar Anexo', ['class' => 'btn btn-success', 'id' => 'btn-save-attachment']) !!}
                            </div>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>

    </div>
</div>

@section('footer')
<link rel="stylesheet" href="{{ asset('plugins/dropify/dist/css/dropify.min.css') }}">
<script src="{{ asset('plugins/dropify/dist/js/dropify.min.js') }}"></script>
    <script>
    $(document).ready(function(){
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


        // Exclusão do norma
        $('.btn-danger').click(function(){
            let id = $(this).data('anexo-id');
            let deleteIt = swal2_warning("Essa ação é irreversível!");
            let obj = {'id': id};

            deleteIt.then(resolvedValue => {
                ajaxMethod('POST', "{{ URL::route('docs.anexo.deletar') }}", obj).then(response => {
                    if(response.response != 'erro') {
                        swal2_success("Excluído!", "Anexo excluído com sucesso.");
                    } else {
                        swal2_alert_error_support("Tivemos um problema ao excluir o anexo.");
                    }
                }, error => {
                    console.log(error);
                });
            }, error => {
                swal.close();
            });
        });

    });
    </script>
@endsection
