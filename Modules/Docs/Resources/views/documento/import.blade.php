@extends('layouts.app')

@extends('layouts.menuDocs')
@yield('menu')


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
                            {!! Form::hidden('codigoDocumento', $codigo) !!}
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
                                    {!! Form::file('doc_uploaded', ['class' => 'dropify', 'id' => 'input-file-now', 'required' => 'required']) !!}
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
    });
    </script>
@endsection