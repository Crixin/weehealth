@extends('core::layouts.app')

@extends('core::layouts.menuDocs')
@yield('menu')

@section('content')

    <!-- O que fazer nestas situações? -->
    
    <!-- jQuery -->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('plugins/dropify/dist/css/dropify.min.css') }}">

	<div class="page-wrapper">
        <div class="container-fluid">
            

            <div class="row page-titles">
                <div class="col-md-6 col-6 align-self-center">
                    <h3 class="text-themecolor m-b-0 m-t-0">Visualização de Documento</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ URL::route('home') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ URL::route('documentacao') }}">Documentação</a></li>
                        <li class="breadcrumb-item active">Visualização de Documento</li>
                    </ol>
                </div>
                <div class="col-3">
                    <button class="btn btn-lg btn-info" type="button" data-toggle="collapse" data-target="#multiCollapseExample2" aria-expanded="false" aria-controls="multiCollapseExample2">Ver Linha do Tempo</button>
                </div>
                <?php $revisoes = \App\Classes\Helpers::instance()->getListAllReviewsDocument($nome); ?>
                @if( Auth::user()->setor_id == Constants::$ID_SETOR_QUALIDADE  &&  count($revisoes) > 1)
                    <div class="col-3">
                        <button class="btn btn-lg btn-warning" type="button" data-toggle="collapse" data-target="#revisoesDoc" aria-expanded="false" aria-controls="revisoesDoc">Revisões Anteriores</button>
                    </div>
                @endif
            </div>
            
            
            <!-- Start Page Content -->
            <div class="row">
                
                <div class="col-md-12">
                    <div class="card card-outline-danger">
                        <div class="card-header">
                            <h4 class="m-b-0 text-white">Este documento está obsoleto</h4>
                        </div>
                    </div>
                </div>

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

                        <!-- Revisões do Documento -->
                        <div class="row">
                            <div class="col col-centered">
                                <div class="collapse multi-collapse" id="revisoesDoc">
                                    <div class="card card-body text-center">

                                        <div class="row">
                                           <div class="col-md-12 col-sm-12 p-20">
                                                <h3 class="card-title text-success">Revisões do documento: <b>{{ $nome }}</b></h3>
                                                <div class="list-group">
                                                    @if(count($revisoes) > 1)
                                                        @foreach($revisoes as $rev)
                                                            {!! Form::open(['route' => 'documentacao.make-doc-from-name', 'method' => 'POST', 'target' => '_blank']) !!}
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

                        <div class="row">
                            <div class="col-md-9"></div>
                            <div class="col-md-3 mb-4 pull-right">
                                <a href="{{ asset('plugins/onlyoffice-php/doceditor.php?fileID=').$docPath.'&type=embedded' }}" class="btn col-md-12 btn-success"  target="_blank"> <i class="mdi mdi-eye"></i> Visualizar </a>
                            </div>
                        </div>


                        <div class="row h-100">
                            <!-- <iframe src="{{url('documentacao/make-doc/'.$document_id)}}" frameborder="0" width="100%" height="600px"></iframe> -->
                            <iframe src="{{ asset('plugins/onlyoffice-php/doceditor.php?fileID=').$docPath.'&type=embedded' }}" frameborder="0" width="100%" height="600px"></iframe>
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


 @if($resp)
    <script src="{{ asset('js/utils-speed.js') }}"></script>    
    <script>
        showToast("{{$resp['title']}}", "{{$resp['msg']}}", "{{$resp['status']}}");
    </script>
@endif


@endsection