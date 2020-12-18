@extends('layouts.app')

@extends('layouts.menuDocs')
@yield('menu')


@section('page_title', __('page_titles.docs.documento.factory'))


@section('breadcrumbs')

    <li class="breadcrumb-item"><a href="{{ route('core.home') }}"> @lang('page_titles.general.home') </a></li>
    <li class="breadcrumb-item"><a href="{{ route('docs.documento') }}"> @lang('page_titles.docs.documento.index') </a></li>
    <li class="breadcrumb-item active"> @lang('page_titles.docs.documento.factory') </li>    

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
                                    <h3>Novo Documento:</h3>

                                        <!-- Editor -->
                                        <div class="container">
                                            <iframe width="100%" id="speed-onlyoffice-editor" src="{{ asset('plugins/onlyoffice-php/doceditor.php?&user=&fileID=').$docPath }}"> </iframe>
                                        </div>
                                        <!-- End Editor -->
                                                
                                        
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