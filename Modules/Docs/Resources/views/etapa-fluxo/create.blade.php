@extends('layouts.app')




@section('page_title', __('page_titles.docs.etapa-fluxo.create'))


@section('breadcrumbs')

    <li class="breadcrumb-item"><a href="{{ route('core.home') }}"> @lang('page_titles.general.home') </a></li>
    <li class="breadcrumb-item"><a href="{{ route('docs.fluxo.etapa-fluxo', ['fluxo_id' => $fluxo->id]) }}"> @lang('page_titles.docs.etapa-fluxo.index') </a></li>
    <li class="breadcrumb-item active"> @lang('page_titles.docs.etapa-fluxo.create') </li>    

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

                <form method="POST" action="{{ route('docs.fluxo.etapa-fluxo.salvar', ['fluxo_id' => $fluxo->id]) }}"> 
                    {{ csrf_field() }}
                    
                    @component(
                        'docs::components.etapa-fluxo', 
                        [
                            'etapaEdit' => [],
                            'nome' => '',
                            'descricao' => '',
                            'perfis' => $perfis,
                            'status' => $status,
                            'notificacoes' => $notificacoes,
                            'tiposAprovacao' => $tiposAprovacao,
                            'etapasRejeicao' => $etapasRejeicao,
                            'fluxo' => []
                        ]
                    )
                    @endcomponent
                        
                    <div class="form-actions ">
                        <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> @lang('buttons.general.save')</button>
                        <a href="{{ route('docs.fluxo.etapa-fluxo', ['fluxo_id' => $fluxo->id]) }}" class="btn btn-inverse"> @lang('buttons.general.back')</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
    
@endsection