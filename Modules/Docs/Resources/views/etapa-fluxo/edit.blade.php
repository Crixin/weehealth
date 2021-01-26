@extends('layouts.app')




@section('page_title', __('page_titles.docs.etapa-fluxo.update'))


@section('breadcrumbs')

    <li class="breadcrumb-item"><a href="{{ route('docs.home') }}"> @lang('page_titles.general.home') </a></li>
    <li class="breadcrumb-item"><a href="{{ route('docs.fluxo.etapa-fluxo', ['fluxo_id' => $etapaEdit->docsFluxo->id] ) }}"> @lang('page_titles.docs.etapa-fluxo.index') </a></li>
    <li class="breadcrumb-item active"> @lang('page_titles.docs.etapa-fluxo.update') </li>    

@endsection



@section('content')

    <div class="col-12">
        <div class="card">
            <div class="card-body">


                @component('components.validation-error', ['errors']) @endcomponent

                @if(Session::has('message'))
                    @component('components.alert')@endcomponent

                    {{ Session::forget('message') }}
                @endif

                <form method="POST" action="{{ route('docs.fluxo.etapa-fluxo.alterar', ['id' => $etapaEdit->id, 'fluxo_id' => $etapaEdit->docsFluxo->id]) }}" >
                    {{ csrf_field() }}
                    <input type="hidden" name="idEtapa" value="{{ $etapaEdit->id }}">
                    <input type="hidden" name="fluxo_id" value="{{ $etapaEdit->docsFluxo->id }}">
                    <input type="hidden" name="ordem" value="{{ $etapaEdit->ordem }}">
                    @component(
                        'docs::components.etapa-fluxo', 
                        [
                            'etapaEdit' => $etapaEdit,
                            'nome' => $etapaEdit->nome,
                            'descricao' => $etapaEdit->descricao,
                            'perfis' => $perfis,
                            'status' => $status,
                            'notificacoes' => $notificacoes,
                            'tiposAprovacao' => $tiposAprovacao,
                            'etapasRejeicao' => $etapasRejeicao
                        ]
                    )
                    @endcomponent
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> @lang('buttons.general.save')</button>
                        <a href="{{ route('docs.fluxo.etapa-fluxo', ['fluxo_id' => $etapaEdit->docsFluxo->id]) }}" class="btn btn-inverse"> @lang('buttons.general.back')</a>
                    </div>

                </form>

            </div>
        </div>
    </div>
    
@endsection