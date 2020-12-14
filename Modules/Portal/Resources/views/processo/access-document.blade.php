@extends('layouts.app')




@section('page_title', __('page_titles.portal.process.index'))


@section('breadcrumbs')

    <li class="breadcrumb-item"><a href="{{ route('portal.home') }}"> @lang('page_titles.general.home') </a></li>
    <li class="breadcrumb-item"> @lang('page_titles.portal.process.index') </li>    
    <li class="breadcrumb-item"> @lang('page_titles.portal.process.search') </li>    
    <li class="breadcrumb-item"> @lang('page_titles.portal.process.list_registers') </li>    
    <li class="breadcrumb-item"> @lang('page_titles.portal.process.register_documents') </li>    
    <li class="breadcrumb-item active"> @lang('page_titles.portal.process.document') </li>    
    
@endsection



@section('content')

	<div class="col-12">
		<div class="card">
			<div class="card-body">

                @if(Session::has('message'))
                    @component('components.alert')
                    @endcomponent

                    {{ Session::forget('message') }}
                @endif


                @if($documento->tipo == 'pdf')
                    @component('portal::components.visualizadores.pdf',compact('documento','permissoes')) @endcomponent
                @elseif(in_array($documento->tipo, $extensao_onlyoffice))
                    @component('portal::components.visualizadores.onlyOffice', compact('documento', 'permissoes', 'valor_doc_aprovado')) @endcomponent
                @elseif(in_array($documento->tipo, $extensao_imagem))
                    @component('portal::components.visualizadores.imagem', compact('documento')) @endcomponent
                @elseif(in_array($documento->tipo, $extensao_video))
                    @component('portal::components.visualizadores.video',compact('documento','permissoes')) @endcomponent
                @elseif($documento->tipo == 'mp3')
                    @component('portal::components.visualizadores.audio',compact('documento', 'permissoes')) @endcomponent
                @endif
                

                <hr>
                <div class="row">
                    <div class="col-md-6">
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
                    <div class="col-md-6">
                        <h4> @lang('document.title_approval_board') </h4>

                        @if ($permissoes['usa_aprovar'])
                            @if (empty($documento->status))

                                <blockquote>
                                    <!-- Nav tabs (Aprovar e Rejeitar) -->
                                    <ul class="nav nav-tabs nav-justified customtab" role="tablist">
                                        <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#aprovar" role="tab"><span class="hidden-sm-up"><i class="ti-home"></i></span> <span class="hidden-xs-down"> <i class="mdi mdi-check"></i> @lang('document.approval.approve') </span></a> </li>
                                        <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#rejeitar" role="tab"><span class="hidden-sm-up"><i class="ti-user"></i></span> <span class="hidden-xs-down"> <i class="mdi mdi-close"></i> @lang('document.approval.reject') </span></a> </li>
                                    </ul>
                                    <!-- Tab panes (Conteúdos) -->
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="aprovar" role="tabpanel">
                                            <div class="p-20">
                                                <h3> @lang('document.approval.h3_approve') </h3>
                                                <h4> @lang('document.approval.h4_approve') </h4>
                                                <p>@lang('document.approval.p_approve')</p>
                                            </div>
                                            <div class="col-md-12 text-center">
                                                <form action="{{ route('portal.processo.documento.aprovar') }}" method="post">
                                                    {{ csrf_field() }}
                                                    <input type="hidden" name="id-documento" value="{{ $documento->id }}">
                                                    <button type="submit" class="btn btn-lg btn-success"> @lang('document.approval.approve_document') </button>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="tab-pane  p-20" id="rejeitar" role="tabpanel">
                                            <h3> @lang('document.approval.h3_reject') </h3>
                                            <h4> @lang('document.approval.h4_reject') </h4>
                                            <p>@lang('document.approval.p_reject')</p>

                                            <form action="{{ route('portal.processo.documento.rejeitar') }}" method="post">
                                                {{ csrf_field() }}

                                                <div class="col-md-12 text-center">
                                                    <div class="form-group">
                                                        <label> @lang('document.document.justification') </label>
                                                        <textarea name="justificativa-rejeicao" class="form-control" rows="4" required ></textarea>
                                                    </div>
                                                    <input type="hidden" name="id-documento" value="{{ $documento->id }}">
                                                    <button type="submit" class="btn btn-lg btn-warning"> @lang('document.approval.reject_document') </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </blockquote>    
                            @else

                                <blockquote>
                                    <p class="text-center text-info" style="font-size: x-large;"> @lang('document.approval.not_need_approve', ['status' => $documento->status]) </p>
                                </blockquote>
                            @endif                                
                        @else
                            
                            <blockquote>
                                <p class="text-center text-info" style="font-size: x-large;"> @lang('action.messages.not_allowed') </p>
                            </blockquote>
                        @endif
                    </div>
                </div>
                
                <div class="col-md-12 m-t-40">
                    <div class="col-md-2 pull-right">
                        <a href="{{ route('portal.processo.listarDocumentos', ['_idRegistro' => $documento->idRegistro]) }}" class="btn btn-secondary btn-lg btn-block pull-right" id="botao-voltar-detalhes-doc"> @lang('buttons.general.back') </a>
                    </div>

                    @if ($permissoes['usa_excluir'])
                        <div class="col-md-2 pull-right">
                            <a href="#" class="btn waves-effect waves-light btn-danger btn-lg btn-block pull-right sa-warning" data-id-documento="{{ $documento->id }}"><i class="mdi mdi-delete"></i>@lang('buttons.general.delete')</a>
                        </div>
                    @endif
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
                ajaxMethod('POST', "{{ URL::route('portal.deletar.documento') }}", obj).then(response => {
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