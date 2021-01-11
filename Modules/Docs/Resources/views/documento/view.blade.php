@extends('layouts.app')




@section('page_title', __('page_titles.docs.documento.view'))


@section('breadcrumbs')

    <li class="breadcrumb-item"><a href="{{ route('core.home') }}"> @lang('page_titles.general.home') </a></li>
    <li class="breadcrumb-item"><a href="{{ route('docs.documento') }}"> @lang('page_titles.docs.documento.index') </a></li>
    <li class="breadcrumb-item active"> @lang('page_titles.docs.documento.view') </li>  

@endsection

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-body">
            <div class="col-3 mb-3">
                <button class="btn  btn-info" type="button" data-toggle="collapse" data-target="#multiCollapseExample2" aria-expanded="false" aria-controls="multiCollapseExample2"><i class="mdi mdi-chart-timeline"></i> Linha do Tempo</button>
            </div>
            <!-- Timeline do Documento -->
            @component(
                'docs::components.linha-tempo-documento', 
                [
                    'historico' => $historico
                ]
            )
            @endcomponent
            <!-- FIM Timeline do Documento -->
            <input type="hidden" name="idDocumentoOrigem" id="idDocumentoOrigem" value="{{$documento->id}}">
            <!-- Start Page Content -->
            <div class="row">
                <!-- Card Principal -->
                <div class="col-md-12 card" style="min-height: 600px">
                    <div class="card-body">
                        <!-- Revisões do Documento -->
                        <div class="row">
                            <div class="col col-centered">
                                <div class="collapse multi-collapse" id="revisoesDoc">
                                    <div class="card card-body text-center">

                                        <div class="row">
                                           <div class="col-md-12 col-sm-12 p-20">
                                                <h3 class="card-title text-success">Revisões do documento: <b>{{ $documento->nome ?? '' }}</b></h3>
                                                <div class="list-group">
                                                    @if(count($revisoes) > 1)
                                                        @foreach($revisoes as $rev)
                                                            {!! Form::open(['route' => 'docs.documento', 'method' => 'POST', 'target' => '_blank']) !!}
                                                                {!! Form::hidden('nome', $rev) !!}
                                                                {!! Form::hidden('tipo_doc', $tipo_doc) !!}
                                                                {!! Form::hidden('document_id', $document_id) !!}
                                                                <button type="submit" class="list-group-item btn-block mt-3">  <span style="font-size: 20px">Revisão <b>{{ explode(".html", explode("_rev", $rev)[1])[0] }}</b>:</span> {{ explode(Constants::$SUFIXO_REVISAO_NOS_TITULO_DOCUMENTOS, $rev)[0] }}  </button>
                                                            {!! Form::close() !!}
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Título e Validade do Documento (apenas texto) -->
                        <div class="row">
                            <div class="col-md-12 col-sm-12 p-20">
                                <h2 class="card-title"><b>{{ $documento->nome ?? '' }}</b> <small class="text-success"> &nbsp; | &nbsp; Previsão Próxima revisão: {{ Carbon\Carbon::parse($documento->validade ?? '')->format('d/m/Y') }}</small></h2>
                            </div>
                        </div>

                        <!-- Editor -->
                        <div class="row iframe_box">
                            <iframe width="100%" id="speed-onlyoffice-editor" src="{{ asset('plugins/onlyoffice-php/doceditor.php?action=review&user=&fileID=').$docPath }}" frameborder="0" width="100%" height="600px"> </iframe>
                        </div>
                        
                        <!-- End Editor -->

                        <div class="col-lg-12 col-md-12">
                            <br>
                            <div class=" pull-right">
                                <button   type="button" class="btn btn-info Anexos" data-id="{{$documento->id}}">@lang('buttons.general.attachments')</button>
                                <a href="{{ route('docs.documento') }}" type="button" class="btn btn-inverse">@lang('buttons.general.back')</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END Page Content -->
        </div>
    </div>
</div>

@endsection
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
@include('docs::modal/anexo-documento',
    [
        'comportamento_modal' => 'EDICAO'
    ]
)

@section('footer')

<script>
    $(document).ready(function(){

        $('.Anexos').click(function(){
            $('#modal-anexos').modal('show');
            let origem = $('#idDocumentoOrigem').val(); 
            $('#idDocumento').val(origem);
        });

    });
</script>
@endsection