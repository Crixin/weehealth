@extends('layouts.app')

@extends('layouts.menuDocs')
@yield('menu')


@section('page_title', __('page_titles.docs.tipo-documento.update'))


@section('breadcrumbs')

    <li class="breadcrumb-item"><a href="{{ route('docs.home') }}"> @lang('page_titles.general.home') </a></li>
    <li class="breadcrumb-item"><a href="{{ route('docs.tipo-documento') }}"> @lang('page_titles.docs.tipo-documento.index') </a></li>
    <li class="breadcrumb-item active"> @lang('page_titles.docs.tipo-documento.update') </li>    

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

                <form method="POST" action="{{ route('docs.tipo-documento.alterar') }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="idTipoDocumento" value="{{ $tipoDocumento->id }}">
                    @component(
                        'docs::components.tipo-documento', 
                        [
                            'tipoDocumentoEdit' => $tipoDocumento,
                            'nome' => $tipoDocumento->nome,
                            'descricao' => $tipoDocumento->descricao,
                            'sigla' => $tipoDocumento->sigla,
                            'tipo_documento_pai' => $tipoDocumento->tipo_documento_pai_id,
                            'fluxos' => $fluxos,
                            'periodosVigencia' => $tipoDocumento->periodo_vigencia_id,
                            'periodosAviso' => $tipoDocumento->periodo_aviso_id,
                            'tiposDocumento' => $tiposDocumento,
                            'padroesCodigo' => $padroesCodigo,
                        ]
                    )
                    @endcomponent
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> @lang('buttons.general.save')</button>
                        <a href="{{ route('docs.tipo-documento') }}" class="btn btn-inverse"> @lang('buttons.general.back')</a>
                    </div>

                </form>

            </div>
        </div>
    </div>
    
@endsection
