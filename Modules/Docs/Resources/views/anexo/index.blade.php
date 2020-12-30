@extends('layouts.app')




@section('page_title', __('page_titles.docs.documento.create'))


@section('breadcrumbs')

    <li class="breadcrumb-item"><a href="{{ route('core.home') }}"> @lang('page_titles.general.home') </a></li>
    <li class="breadcrumb-item"><a href="{{ route('docs.documento') }}"> @lang('page_titles.docs.documento.index') </a></li>
    <li class="breadcrumb-item active"> @lang('page_titles.docs.documento.create') </li>    

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
                
                    @component(
                        'docs::components.anexo-documento', 
                        [
                          "tituloDocumento"  => $titulo,
                          "id"     => $id,
                          "anexos" => $anexos
                        ]
                    )
                    @endcomponent

                <form method="POST" action="{{route('docs.documento.proxima-etapa')}}" name="proximaEtapa" id="proximaEtapa"> 
                    {{ csrf_field() }}
                    <input type="hidden" name="idDocumento" id="idDocumento" value="{{$id}}">
                    <div class="form-actions ">
                        <button type="submit" class="btn btn-success"> <i class="mdi mdi-chevron-double-right"></i> @lang('buttons.general.complete')</button>
                        <a href="{{ route('docs.documento') }}" class="btn btn-inverse"> @lang('buttons.general.back')</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
    
@endsection
