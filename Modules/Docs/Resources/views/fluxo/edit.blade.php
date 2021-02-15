@extends('layouts.app')

@section('page_title', __('page_titles.docs.fluxo.update'))

@section('breadcrumbs')

    <li class="breadcrumb-item"><a href="{{ route('docs.home') }}"> @lang('page_titles.general.home') </a></li>
    <li class="breadcrumb-item"><a href="{{ route('docs.fluxo') }}"> @lang('page_titles.docs.fluxo.index') </a></li>
    <li class="breadcrumb-item active"> @lang('page_titles.docs.fluxo.update') </li>    

@endsection

@section('content')
@include('docs::modal/etapa-fluxo',
[
    "etapa"          => $fluxo,
    "perfis"         => $perfis,
    "status"         => $status,
    "notificacoes"   => $notificacoes,
    "tipoAprovacao"  => $tiposAprovacao,
    "etapasRejeicao" => $etapasRejeicao
])
    <div class="col-12">
        <div class="card">
            <div class="card-body">


                @component('components.validation-error', ['errors'])@endcomponent

                @if(Session::has('message'))
                    @component('components.alert')@endcomponent

                    {{ Session::forget('message') }}
                @endif
                <!--<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    Ao salvar alguma alteração no fluxo ou em suas etapas, será gerado uma nova versão do fluxo.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                    </button>
               </div>-->
                <form method="POST" action="{{ route('docs.fluxo.alterar') }}" id="formFluxoEdit" onsubmit="msgConfirmacao()" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="novaVersaoFluxo" id="novaVersaoFluxo">
                    <input type="hidden" name="idFluxo" value="{{ $fluxo->id }}">
                    <input type="hidden" name="ordemHidden" id="ordemHidden" value="{{$fluxo->docsEtapaFluxoInversao[0]->ordem ?? 0}}"> 
                    @component(
                        'docs::components.fluxo', 
                        [
                            'fluxoEdit' => $fluxo,
                            'nome' => $fluxo->nome,
                            'descricao' => $fluxo->descricao, 
                            'versao' => $fluxo->versao,
                            'grupos' => $grupos,
                            'perfis' => $perfis,
                            'etapas' => $todasEtapas
                        ]
                    )
                    @endcomponent
                    <div class="form-actions">
                        <button type="submit" class="btn btn-success" id="btnEditFluxo"> <i class="fa fa-check"></i> @lang('buttons.general.save')</button>
                        <a href="{{ route('docs.fluxo') }}" class="btn btn-inverse"> @lang('buttons.general.back')</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection