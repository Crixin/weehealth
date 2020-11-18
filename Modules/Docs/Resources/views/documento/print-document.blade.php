@extends('core::layouts.app')

@extends('core::layouts.menuDocs')
@yield('menu')

@section('content')
    
    <!-- jQuery -->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>

	<div class="page-wrapper">
        <div class="container-fluid">
            

            <div class="row page-titles">
                <div class="col-md-6 col-6 align-self-center">
                    <h3 class="text-themecolor m-b-0 m-t-0">Impressão de Documento</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ URL::route('home') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ URL::route('documentacao') }}">Documentação</a></li>
                        <li class="breadcrumb-item active">Impressão de Documento</li>
                    </ol>
                </div>
                <div class="col-3">
                    <button class="btn btn-lg btn-info" type="button" data-toggle="collapse" data-target="#multiCollapseExample2" aria-expanded="false" aria-controls="multiCollapseExample2">Ver Linha do Tempo</button>
                </div>
            </div>
            
            
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

                        <!-- Timeline do Documento -->
                        <div class="row">
                            <div class="col col-centered">
                                <div class="collapse multi-collapse" id="multiCollapseExample2">
                                    <div class="card card-body text-center">

                                        <div class="row">
                                            <div class="col-md-3"></div>
                                            <div class="col-md-6">
                                                <div class="row" style="font-size:14px">
                                                    <div class="form-group col-md-12">
                                                        <?php \Carbon\Carbon::setLocale('pt_BR') ?>
                                                        
                                                        <ul class="timeline text-center">
                                                            @foreach( \App\Classes\Helpers::instance()->getHistoricoDocumento($document_id) as $key => $hist )
                                                                <li class=" {{ $key%2 == 0 ? 'timeline-inverted' : '' }}">
                                                                    <div class="timeline-badge success"  >
                                                                        <i class="mdi mdi-file-document"></i>
                                                                    </div>
                                                                    <div class="timeline-panel">
                                                                        <div class="timeline-heading">
                                                                            <h4 class="timeline-title">{{ ($hist->nome_usuario_responsavel != null) ? $hist->nome_usuario_responsavel : 'Usuário Inválido' }}</h4>
                                                                            <p><small class="text-muted"><i class="fa fa-clock-o"></i> {{ $hist->created_at->diffForHumans() }}</small> </p>
                                                                        </div>
                                                                        <div class="timeline-body">
                                                                            <p>{{ $hist->descricao }}</p>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                            @endforeach     
                                                        </ul>
                                                    </div>
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
                                <h2 class="card-title">
                                    <b>{{ $documentTitle }}</b> 
                                    <small class="text-success"> &nbsp; | &nbsp; Validade: {{ Carbon\Carbon::parse($validity)->format('d/m/Y') }}</small>
                                    
                                    @if (Auth::user()->setor_id == Constants::$ID_SETOR_QUALIDADE)
                                        <span class="pull-right">
                                            @if ($mode === 'without_stripe')
                                                <a href="{{ route('documentacao.print', ['document' => $document_id]) }}" class="btn btn-lg btn-info">Modo Com Tarja</a>
                                            @else
                                                <a href="{{ route('documentacao.printWithoutStripe', ['document' => $document_id]) }}" class="btn btn-lg btn-info">Modo Sem Tarja</a>
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
                                <iframe id="document-iframe" src="" data-src="{{ asset('plugins/onlyoffice-php/doceditor.php?folder=temp&fileID=').$newFilename.'&p=1&action=view' }}" frameborder="0" width="100%" height="1000px"></iframe>
                            @endif
                        </div>
                        
                        <div class="col-lg-12 col-md-12">
                            <br>
                            <div class="col-md-offset-2 col-md-3 pull-right">
                                <a href="{{ route('documentacao') }}" type="button" class="btn waves-effect waves-light btn-block btn-lg btn-secondary">Voltar</a>
                            </div>
                        </div>
                    
                    </div>
                </div>


            </div>
            <!-- End Page Content -->


        </div>
    </div>

@endsection



@section('footer')
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