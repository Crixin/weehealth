@extends('layouts.app')




@section('page_title', __('page_titles.docs.tipo-documento.create'))


@section('breadcrumbs')

    <li class="breadcrumb-item"><a href="{{ route('core.home') }}"> @lang('page_titles.general.home') </a></li>
    <li class="breadcrumb-item"><a href="{{ route('docs.tipo-documento') }}"> @lang('page_titles.docs.tipo-documento.index') </a></li>
    <li class="breadcrumb-item active"> @lang('page_titles.docs.tipo-documento.create') </li>    

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
                
                <form method="POST" action="{{ route('docs.tipo-documento.salvar') }}"  enctype="multipart/form-data">
                    {{ csrf_field() }}
                    
                    @component(
                        'docs::components.tipo-documento', 
                        [
                            'tipoDocumentoEdit' => [],
                            'nome' => '', 
                            'descricao' => '',
                            'sigla' => '',
                            'tipo_documento_pai' => '',
                            'fluxos' => $fluxos,
                            'periodosVigencia' => '',
                            'periodosAviso' => '',
                            'ultimoDocumento' => 0,
                            'tiposDocumento' => $tiposDocumento,
                            'padroesCodigo' => $padroesCodigo,
                            'padroesNumero' => $padroesNumero,
                            'extensoesDocumentos' => $extensoesDocumentos,
                        ]
                    )
                    @endcomponent
                        
                    <div class="form-actions ">
                        <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> @lang('buttons.general.save')</button>
                        <a href="{{ route('docs.tipo-documento') }}" class="btn btn-inverse"> @lang('buttons.general.back')</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
    
@endsection