@extends('layouts.app')

@section('page_title', __('page_titles.docs.documento.view'))

@section('breadcrumbs')

    <li class="breadcrumb-item"><a href="{{ route('core.home') }}"> @lang('page_titles.general.home') </a></li>
    <li class="breadcrumb-item"><a href="{{ route('docs.documento') }}"> @lang('page_titles.docs.documento.index') </a></li>
    <li class="breadcrumb-item active"> @lang('page_titles.docs.documento.view') </li>  

@endsection

@section('content')
<div class="col-md-12">
    <div class="row">
        @if (!$etapaAtual->comportamento_treinamento && $etapaAtual->docsFluxo->coreGrupo->coreUsers->contains("id", Auth::id()))
            @component('docs::components.documento.cancelar-revisao') @endcomponent
        @endif

        @if ($etapaAtual->comportamento_criacao || $etapaAtual->comportamento_edicao)
            @component(
                'docs::components.documento.substituicao', 
                [
                    "documento" => $documento->id,
                    "extensoes" => $extensoesPermitidas
                ]
            ) @endcomponent
        @endif
    </div>

    <div class="card">
        <div class="card-body">
            <legend><b>{{ $documento->nome . " - " . $documento->codigo}}</b></legend>
            <hr>

            <!-- Timeline do Documento -->
            <div class="col-md-12 mb-3">
                <button class="btn btn-info" type="button" data-toggle="collapse" data-target="#multiCollapseExample2" aria-expanded="false" aria-controls="multiCollapseExample2"><i class="mdi mdi-chart-timeline"></i> Linha do Tempo</button>
            </div>
            @component(
                'docs::components.documento.linha-tempo', 
                [
                    'historico' => $historico
                ]
            )
            @endcomponent

            @if ($etapaAtual->comportamento_aprovacao)
                @component(
                    'docs::components.documento.aprovacao',
                    [
                        "documento" => $documento->id
                    ]
                ) @endcomponent
            @endif
            
            <!-- FIM Timeline do Documento -->
            <input type="hidden" name="idDocumentoOrigem" id="idDocumentoOrigem" value="{{$documento->id}}">
            <!-- Start Page Content -->
            <div class="row">
                <!-- Card Principal -->
                <div class="col-md-12 card" style="min-height: 600px">
                    <div class="card-body">

                        <!-- Título e Validade do Documento (apenas texto) -->
                        <div class="row">
                            <div class="col-md-12 col-sm-12 p-20">
                                <h2 class="card-title"><b>{{ $documento->nome ?? '' }}</b> <small class="text-success"> &nbsp; | &nbsp; Previsão Próxima revisão: {{ Carbon\Carbon::parse($documento->validade ?? '')->format('d/m/Y') }}</small></h2>
                            </div>
                        </div>

                        <!-- Editor -->
                        
                        @if ($etapaAtual->comportamento_criacao || $etapaAtual->comportamento_edicao)
                            <div class="row iframe_box">
                                <iframe width="100%" id="speed-onlyoffice-editor" src="{{ asset('plugins/onlyoffice-php/doceditor.php?action=review&user=&fileID=').$docPath }}" frameborder="0" width="100%" height="600px"> </iframe>
                            </div>
                        @endif
                        
                        @if ($etapaAtual->comportamento_treinamento)
                            @component('docs::components.documento.treinamento', ["documento" => $documento]) @endcomponent
                        @endif
                        
                         <!-- End Editor -->

                        <div class="col-lg-12 col-md-12 mt-3">
                            <div class="pull-right">
                                {!! Form::open(['method' => 'POST', 'route' => 'docs.workflow.avancar-etapa']) !!}
                                
                                    {{ Form::token() }}
                                    {!! Form::hidden('documento_id', $documento->id) !!}

                                    @if (!$etapaAtual->comportamento_aprovacao)
                                        {!! Form::submit("Encaminhar para " . $proximaEtapa->nome, ['class' => 'btn btn-success']) !!}
                                    @endif
                                
                                    <button type="button" class="btn btn-info anexos-documento" data-id="{{$documento->id}}">@lang('buttons.general.attachments')</button>
                                    <a href="{{ route('docs.documento') }}" type="button" class="btn btn-inverse">@lang('buttons.general.back')</a>
                                
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END Page Content -->
        </div>
    </div>
</div>

@include('docs::modal/anexo-documento',
    [
        'comportamento_modal' => 'EDICAO',
        'idDocumento' => $documento->id
    ]
)

@endsection

<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>

@section('footer')

<script>
    $(document).ready(function(){

        $('.anexos-documento').click(function(){
            $('#modal-anexos').modal('show');
        });

    });
</script>
@endsection