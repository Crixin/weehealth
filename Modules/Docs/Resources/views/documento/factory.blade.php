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
                            'docs::components.documento.informacao-criacao', 
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
                        <div class="card">
                            <div class="card-body">
                                <h3>Novo Documento:</h3>

                                    <!-- Editor -->
                                    <div class="row iframe_box">
                                        <iframe width="100%" id="onlyoffice-editor" src="{{ asset('plugins/onlyoffice-php/doceditor.php?&user=&fileID=').$docPath }}" frameborder="0" width="100%" height="600px"> </iframe>
                                    </div>

                            </div>
                        </div>
                        {!! Form::open(['route' => 'docs.documento.salvar', 'method' => 'POST', 'id' => 'form-create-document', 'enctype' => 'multipart/form-data']) !!}
                            {{ csrf_field() }}
                            <input type="hidden" name="acao" value="CRIAR">
                            <input type="hidden" name="codigoDocumento" id="codigoDocumento" value="{{$codigo}}">
                            <!--campos do formulario anterior -->
                            @component(
                                'docs::components.documento.input-hidden-criacao', 
                                [
                                    'request' => $request
                                ]
                            )
                            @endcomponent
                            <!--Fim campos do formulario anterior -->
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

@section('footer')
    <script>
    $(document).ready(function(){
        $("#form-create-document").submit(function(e){
            e.preventDefault();
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
                    if(ret.success == true) {
                        swal2_success_not_reload("Sucesso!", "Documento criado com sucesso.");
                        document.location.href="{!! route('docs.documento'); !!}";
                    }else{
                        swal2_alert_error_not_reload("Erro ao criar documento.");
                    }
                }
            });
             
        });
    });
    </script>
@endsection
