@extends('layouts.app')

@section('page_title', __('page_titles.docs.documento.print'))

@section('breadcrumbs')

    <li class="breadcrumb-item"><a href="{{ route('core.home') }}"> @lang('page_titles.general.home') </a></li>
    <li class="breadcrumb-item"><a href="{{ route('docs.documento') }}"> @lang('page_titles.docs.documento.index') </a></li>
    <li class="breadcrumb-item active"> @lang('page_titles.docs.documento.print') </li>    

@endsection

@section('content')
    
    <!-- jQuery -->
    
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="col-3 mb-3">
                    <button class="btn  btn-info" type="button" data-toggle="collapse" data-target="#multiCollapseExample2" aria-expanded="false" aria-controls="multiCollapseExample2"><i class="mdi mdi-chart-timeline"></i> Linha do Tempo</button>
                </div>
                <!-- Timeline do Documento -->
                @component(
                    'docs::components.documento.linha-tempo', 
                    [
                        'historico' => $historico
                    ]
                )
                @endcomponent
                <!-- FIM Timeline do Documento -->

                <!-- Start Page Content -->
                <div class="row">
                    @if ($mode === "with_stripe")
                        <div class="col-md-12">
                            <div class="card card-outline-{{ $messageClass }}">
                                <div class="card-header">
                                    <h4 class="m-b-0 text-white">{{ $message }}</h4>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Card Principal -->
                    <div class="col-md-12 card" style="min-height: 600px">
                        <div class="card-body">
                            <!-- Título e Validade do Documento (apenas texto) -->
                            <div class="row">
                                <div class="col-md-12 col-sm-12 p-20">
                                    <h2 class="card-title">
                                        <b>{{ $documento->nome ?? '' }}</b> 
                                        <small class="text-success"> &nbsp; | &nbsp; Validade: {{ $documento->validade ? date('d/m/Y', $documento->validade) : ' Documento sem validade informada' }}</small>
                                        
                                        @if (Auth::user()->setor_id == $setorQualidade)
                                            <span class="pull-right">
                                                @if ($mode === 'without_stripe')
                                                    <a href="{{ route('docs.documento.imprimir', ['id' => $documento->id, 'tipo' => 2]) }}" class="btn  btn-info"><i class="mdi mdi-sim-off"></i>&nbsp; Com Tarja</a>
                                                @else
                                                    <a href="{{ route('docs.documento.imprimir', ['id' => $documento->id, 'tipo' => 1]) }}" class="btn  btn-info"><i class="mdi mdi-sim"></i>&nbsp; Modo Sem Tarja</a>
                                                @endif
                                            </span>
                                        @endif
                                    </h2>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-3 col-md-4 col-sm-3 col-xs-12">
                                    <div class="ribbon-wrapper card">
                                        <div class="ribbon ribbon-bookmark ribbon-info">Passo 1</div>
                                        <p class="ribbon-content">Ao abrir essa tela, já registramos sua intenção de imprimir o documento.</p>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-4 col-sm-3 col-xs-12">
                                    <div class="ribbon-wrapper card">
                                        <div class="ribbon ribbon-bookmark ribbon-info">Passo 2</div>
                                        <p class="ribbon-content">Então, vamos lhe ajudar neste processo: clique no ícone <img src="{{ asset('images/icon/menu-file.png') }}" alt="Ícone superior esquerdo do editor"> superior esquerdo do editor.</p>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-4 col-sm-3 col-xs-12">
                                    <div class="ribbon-wrapper card">
                                        <div class="ribbon ribbon-bookmark ribbon-info">Passo 3</div>
                                        <p class="ribbon-content">Feito isso, o menu lateral será apresentado e você verá o texto <span class="font-weight-bold">Imprimir</span>: clique nele!</p>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-4 col-sm-3 col-xs-12">
                                    <div class="ribbon-wrapper card">
                                        <div class="ribbon ribbon-bookmark ribbon-info">Passo 4</div>
                                        <p class="ribbon-content">Agora, basta você selecionar sua impressora ou salvar o arquivo, caso desejar!</p>
                                    </div>
                                </div>
                            </div>


                            <div class="container iframe_box">
                                @if ($mode === 'without_stripe')
                                    <iframe id="document-iframe" src="" data-src="{{ asset('plugins/onlyoffice-php/doceditor.php?fileID=').$filename.'&p=1&action=view' }}" frameborder="0" width="100%" height="1000px"></iframe>
                                @else
                                    <iframe id="document-iframe" src="" data-src="{{ asset('plugins/onlyoffice-php/doceditor.php?folder=temp&fileID=').$filename.'&p=1&action=view' }}" frameborder="0" width="100%" height="1000px"></iframe>
                                @endif
                            </div>
                            
                            <div class="col-lg-12 col-md-12">
                                <br>
                                <div class=" pull-right">
                                    <a href="{{ route('docs.documento') }}" type="button" class="btn btn-inverse">@lang('buttons.general.back')</a>
                                </div>
                            </div>
                        
                        </div>
                    </div>
                </div>
                <!-- End Page Content -->
            </div>
        </div>
    </div>

@endsection

@section('footer')
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('plugins/blockUI/jquery.blockUI.js') }}"></script>
    <script>
        $('.iframe_box').block({ 
            message: '<h3>Carregando...</h3>', 
            css: { 
                padding:'10px 0 0 0',
                color:'#fff',
                'border-radius':'20px',
                'background-color':'rgba(255, 255, 255, 0.7)'
            } 
        }); 

        setTimeout(() => {
            $('iframe').map((key, iframe) => $(iframe).attr('src', $(iframe).attr('data-src')));
            $('.iframe_box').unblock();
        }, 8000);
    </script>
@endsection