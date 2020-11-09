@extends('core::layouts.app')

@extends('core::layouts.menuPortal')
@yield('menu')


@section('page_title', __('page_titles.ged.index'))


@section('breadcrumbs')

    <li class="breadcrumb-item"><a href="{{ route('home') }}"> @lang('page_titles.general.home') </a></li>
    <li class="breadcrumb-item"> @lang('page_titles.ged.index') </li>    
    <li class="breadcrumb-item"> @lang('page_titles.ged.search') </li>    
    <li class="breadcrumb-item active"> @lang('page_titles.process.document') </li>    
    
@endsection



@section('content')

	<div class="col-12">
		<div class="card">
			<div class="card-body">

                @if(Session::has('message'))
                    @component('portal.componentes.alert') @endcomponent
                    {{ Session::forget('message') }}
                @endif

                @if($documento->tipo == 'pdf')
                    @component('components.visualizadores.pdf',compact('documento','permissoes')) @endcomponent
                @elseif(in_array($documento->tipo, $extensao_onlyoffice))
                    @component('components.visualizadores.onlyOffice', compact('documento', 'permissoes', 'valor_doc_aprovado')) @endcomponent
                @elseif(in_array($documento->tipo, $extensao_imagem))
                    @component('components.visualizadores.imagem', compact('documento')) @endcomponent
                @elseif(in_array($documento->tipo, $extensao_video))
                    @component('components.visualizadores.video',compact('documento','permissoes')) @endcomponent
                @elseif($documento->tipo == 'mp3')
                    @component('components.visualizadores.audio',compact('documento', 'permissoes')) @endcomponent
                @endif
                

                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <h4> @lang('document.title_doc_information') </h4>
                        <blockquote>
                            <ul class="list-icons">
                                @foreach ($documento->listaIndice as $indice)
                                    @if ($indice->identificador == Constants::$IDENTIFICADOR_JUSTIFICATIVA)
                                        @if (!empty($indice->valor))
                                            <li><a href="javascript:void(0)" class="cursor-default"><i class="fa fa-chevron-right text-success"></i> {{ $indice->descricao }}: <span class="font-weight-bold">{{ $indice->valor }}</span> </a></li>
                                        @endif
                                    @else
                                        @if ($indice->identificador == Constants::$IDENTIFICADOR_STATUS  &&  empty($indice->valor))
                                            <li><a href="javascript:void(0)" class="cursor-default"><i class="fa fa-chevron-right text-success"></i> {{ $indice->descricao }}: <span class="font-weight-bold">Pendente</span> </a></li>
                                        @elseif ($indice->identificador == Constants::$IDENTIFICADOR_TAMANHO_DOC)
                                            <li><a href="javascript:void(0)" class="cursor-default"><i class="fa fa-chevron-right text-success"></i> {{ $indice->descricao }}: <span class="font-weight-bold">{{ Helper::formatSizeUnits($indice->valor) }}</span> </a></li>
                                        @else
                                            <li><a href="javascript:void(0)" class="cursor-default"><i class="fa fa-chevron-right text-success"></i> {{ $indice->descricao }}: <span class="font-weight-bold">{{ property_exists($indice, "valor") ? $indice->valor : '' }}</span> </a></li>
                                        @endif
                                    @endif
                                @endforeach
                            </ul>
                        </blockquote>
                    </div>
                </div>
                
                <div class="col-md-12 m-t-40">
                    <div class="col-md-2 pull-right">
                        <a href="{{ route('ged.list-document', [$empresaProcesso, $idRegistro]) }}" class="btn waves-effect waves-light btn-lg btn-block btn-secondary pull-right mt-4" >@lang('buttons.general.back') </a>
                    </div>
                </div>

			</div>
		</div>
	</div>
    
@endsection


@section('footer')
    <!-- SweetAlert2 -->
    <script>
    
        // Exclusão de documento
        $('.sa-warning').click(function(){ 
            let idDocumento = $(this).data('id-documento');
            let deleteIt    = swal2_warning("Essa ação é irreversível!");
            let baseURL     = "{{ url('/') }}";
            let idRegistro  = "{{ $documento->idRegistro }}";
            let obj = {'documento_id': idDocumento};

            deleteIt.then(resolvedValue => {
                ajaxMethod('POST', "{{ URL::route('deletar.documento') }}", obj).then(response => {
                    if(response.response != 'erro') {
                        swal({   
                            title: "Excluído!",   
                            text: "Documento excluído com sucesso.",
                            type: "success"
                        }, function(){   
                            window.location.href = baseURL + '/processo/listarDocumentos/' + idRegistro;
                        });
                    } else {
                        swal2_alert_error_support("Tivemos um problema ao excluir o documento.");
                    }
                }, error => {
                    console.log(error);
                });
            }, error => {
                swal.close();
            });
        });
    </script>
@endsection