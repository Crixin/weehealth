@extends('layouts.app')

@extends('layouts.menuPortal')
@yield('menu')


@section('page_title', __('page_titles.portal.process.index'))


@section('breadcrumbs')

    <li class="breadcrumb-item"><a href="{{ route('portal.home') }}"> @lang('page_titles.general.home') </a></li>
    <li class="breadcrumb-item"> @lang('page_titles.portal.process.index') </li>    
    <li class="breadcrumb-item"> @lang('page_titles.portal.process.search') </li>    
    <li class="breadcrumb-item"> @lang('page_titles.portal.process.list_registers') </li>    
    <li class="breadcrumb-item active"> @lang('page_titles.portal.process.register_documents') </li>    

@endsection



@section('content')

	<div class="col-12">
		<div class="card">
			<div class="card-body">
				@if($possuiDocumento)
                    @if(Session::has('message'))
                        @component('componentes.alert')
                        @endcomponent

                        {{ Session::forget('message') }}
                    @endif
					<div class="table-responsive m-t-40">
						<table id="dataTable-registros" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
							<thead>
								<tr>
									@foreach ( $cabecalho as $indice )
										<th>{{ $indice->descricao }}</th>
                                    @endforeach
                                    <th>Ações</th>
								</tr>
                            </thead>
							<tbody>
								@foreach ($documentos as $documento)
									<tr>
										@foreach($documento->listaIndice as $indice)
                                            @if ($indice->identificador == Constants::$IDENTIFICADOR_TAMANHO_DOC)
                                                <td>{{ Helper::formatSizeUnits($indice->valor) }}</td>
                                            @elseif ($indice->identificador == Constants::$IDENTIFICADOR_STATUS)
                                                @if ( !empty($indice->valor) )
                                                    <td><span class="label label-{{ ($indice->valor == Constants::$VALOR_DOCUMENTO_APROVADO) ? 'success' : 'danger' }}">{{ $indice->valor }}</span> </td>
                                                @else
                                                    <td><span class="label label-warning">PENDENTE</span> </td>                                                    
                                                @endif
                                            @else
                                                <td>{{ $indice->valor or '' }}</td>
                                            @endif
                                        @endforeach
                                        <td style="word-wrap: break-word;min-width: 225px;max-width: 225px;">
                                            @if ($podeExcluir)
                                                <a href="#" class="btn waves-effect waves-light btn-danger sa-warning" data-id-documento="{{ $documento->id }}"> <i class="mdi mdi-delete"></i> @lang('buttons.general.delete') </a>
                                            @endif
                                            
                                            {{-- Visualizar estará sempre disponível --}}
                                            <a href="{{ route('portal.processo.acessarDocumento', ['_idDocumento' => $documento->id]) }}" class="btn waves-effect waves-light btn-secondary m-l-10"> <i class="mdi mdi-file-document"></i> @lang('buttons.general.access') </button>
                                        </td>
									</tr>
								@endforeach	
							</tbody>
						</table>
                    </div>
                    <div class="col-md-12 m-t-40">
                        <div class="col-md-4 pull-right">
                            <a href="{{ route('portal.processo.buscar', ['idEmpresa' => session('identificadores')['_idEmpresa'], 'idProcesso' => session('identificadores')['_idProcesso'] ] ) }}" class="btn btn-secondary btn-lg btn-block pull-right"> @lang('buttons.general.back') para pesquisa </a>
                        </div>
                    </div>
				@else
				
					<p class="text-center text-danger" style="font-size: x-large;"> @lang('action.messages.no_documents') </p>
					<div class="container">
						<hr>	
						<div class="row">
							<div class="col"></div>
							<div class="col-4">
                                <a href="{{ route('portal.processo.buscar', ['idEmpresa' => session('identificadores')['_idEmpresa'], 'idProcesso' => session('identificadores')['_idProcesso'] ] ) }}" class="btn btn-secondary btn-lg btn-block pull-right"> @lang('buttons.general.back') para pesquisa </a>
							</div>
							<div class="col"></div>
						</div>
					</div>
				@endif

			</div>
		</div>
	</div>
    
@endsection


@section('footer')
    <!-- This is data table -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>

    <!-- start - This is for export functionality only -->
    <script src="{{ asset('js/dataTables/dataTables-1.2.2.buttons.min.js') }}"></script>
    <script src="{{ asset('js/dataTables/buttons-1.2.2.flash.min.js') }}"></script>
    <script src="{{ asset('js/dataTables/jszip-2.5.0.min.js') }}"></script>
    <script src="{{ asset('js/dataTables/pdfmake-0.1.18.min.js') }}"></script>
    <script src="{{ asset('js/dataTables/vfs_fonts-0.1.18.js') }}"></script>
    <script src="{{ asset('js/dataTables/buttons-1.2.2.html5.min.js') }}"></script>
    <script src="{{ asset('js/dataTables/buttons-1.2.2.print.min.js') }}"></script>
    <!-- end - This is for export functionality only -->

    <script>
        $(document).ready(function() {
            $('#dataTable-registros').DataTable({
                "language": {
                    "sEmptyTable": "Nenhum registro encontrado",
                    "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                    "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
                    "sInfoFiltered": "(Filtrados de _MAX_ registros)",
                    "sInfoPostFix": "",
                    "sInfoThousands": ".",
                    "sLengthMenu": "_MENU_ resultados por página",
                    "sLoadingRecords": "Carregando...",
                    "sProcessing": "Processando...",
                    "sZeroRecords": "Nenhum registro encontrado",
                    "sSearch": "Pesquisar",
                    "oPaginate": {
                        "sNext": "Próximo",
                        "sPrevious": "Anterior",
                        "sFirst": "Primeiro",
                        "sLast": "Último"
                    },
                    "oAria": {
                        "sSortAscending": ": Ordenar colunas de forma ascendente",
                        "sSortDescending": ": Ordenar colunas de forma descendente"
                    }
                },
                dom: 'Bfrtip',
                buttons: [
                    { extend: 'excel',  text: 'Excel' },
                    { extend: 'pdf',    text: 'PDF' },
                    { extend: 'print',  text: 'Imprimir' }
                ]
            });
        });
    </script>

    <!-- SweetAlert2 -->
    <script>
    
        // Exclusão de documento
        $('.sa-warning').click(function(){
            let idDocumento = $(this).data('id-documento');
            let deleteIt = swal2_warning("Essa ação é irreversível!");
            let obj = {'documento_id': idDocumento};

            deleteIt.then(resolvedValue => {
                ajaxMethod('POST', "{{ URL::route('portal.deletar.documento') }}", obj).then(response => {
                    if(response.response != 'erro') {
                        swal2_success("Excluído!", "Documento excluído com sucesso.");
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