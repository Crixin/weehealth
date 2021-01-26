@extends('layouts.app')

@section('page_title', __('page_titles.docs.documento.import'))

@section('breadcrumbs')

    <li class="breadcrumb-item"><a href="{{ route('core.home') }}"> @lang('page_titles.general.home') </a></li>
    <li class="breadcrumb-item"><a href="{{ route('docs.documento') }}"> @lang('page_titles.docs.documento.index') </a></li>
    <li class="breadcrumb-item active"> @lang('page_titles.docs.documento.import') </li>    

@endsection



@section('content')

    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <input type="hidden" name="permitirAnexo" id="permitirAnexo" value="{{$permissaoEtapa->permitir_anexo}}">    
                @component('components.validation-error', ['errors'])@endcomponent

                @if(Session::has('message'))
                    @component('components.alert')@endcomponent

                    {{ Session::forget('message') }}
                @endif

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

                        @component(
                            'docs::components.informacao-criacao-documento', 
                            [
                                'text_tituloDocumento'      => $titulo,
                                'text_codigoDocumento'      => $codigo,
                                'text_nivelAcessoDocumento' => $nivelAcesso,
                                'text_validadeDocumento'    => $validade,
                                'text_setorDono'       => $setor,
                                'text_copiaControlada' => $copiaControlada,
                                'text_tipo_documento'  => $tipoDocumento,
                                'text_classificacao'   => $classificacao
                            ]
                        )
                        @endcomponent
                        {!! Form::open(['route' => 'docs.documento.salvar', 'method' => 'POST', 'id' => 'form-upload-document', 'enctype' => 'multipart/form-data']) !!}
                            {{ csrf_field() }}
                            <input type="hidden" name="acao" value="IMPORTAR">
                            <input type="hidden" name="codigoDocumento" id="codigoDocumento" value="{{$codigo}}">
                            
                            <!--campos do formulario anterior -->
                            @component(
                                'docs::components.input-hidden-criacao-documento', 
                                [
                                    'request' => $request
                                ]
                            )
                            @endcomponent
                            <!--Fim campos do formulario anterior -->
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title"> Upload de documentos </h4>
                                    <label for="input-file-now">Por favor, anexe o arquivo que você deseja controlar dentro do sistema.</label>
                                    {!! Form::file('doc_uploaded', ['class' => 'dropify', 'id' => 'input-file-now', 'required' => 'required', 'accept' => '.doc, .xls, .DOC, .XLS, .docx, .xlsx, .DOCX, .XLSX']) !!}
                                </div>
                            </div>
                            <div class="form-actions ">
                                <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> @lang('buttons.general.save')</button>
                                <a href="{{ route('docs.documento.novo') }}" class="btn btn-inverse"> @lang('buttons.general.back')</a>
                            </div>
                        
                        {!! Form::close() !!}

                    </div>
                </div>
            </div>
        </div>
    </div>
    
@endsection
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
@include('docs::modal/anexo-documento',
    [
        'comportamento_modal' => 'CRIACAO'
    ]
)
@section('footer')
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
        });


        $("#form-upload-document").submit(function(e){
            e.preventDefault();

            if( $("#input-file-now").val() == null  ||  $("#input-file-now").val() == "" ) {
                showToast('Opa!', 'Você precisa escolher um arquivo.', 'error');
                return;
            }
            /*if( $("#codigoDocumento").val() == null  ||  $("#codigoDocumento").val() == "" ) {
                showToast('Opa!', 'Você precisa fornecer um código para que o documento seja salvo.', 'error');
                return;
            }
            */
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
                success: function(ret) {
                    console.log(ret);
                    
                    if(!ret.success){
                       swal2_alert_error_support("Tivemos um problema ao importar o documento.");
                    }
                    
                    let id = ret.data.documento_id;
                    let url ="{!! route('docs.documento.visualizar',['id'=>':id']); !!}";
                    url = url.replace(':id', id);
                    document.location.href=url;
                    

                    /*
                    if($('#permitirAnexo').val() == true){
                        $('#idDocumento').val(ret.data);
                        $("#modal-anexos").modal({ backdrop: 'static', keyboard: false});
                        $("#btn-lista-anexos").trigger('click');
                    }else{
                        swal2_success_not_reload("Sucesso!", "Documento criado com sucesso.");
                        document.location.href="{!! route('docs.documento'); !!}";
                    }
                    */
                },
                error: function(erro){
                    console.log(erro);
                }
            });
             
        });


    });
    </script>
@endsection