@extends('layouts.app')

@extends('layouts.menuPortal')
@yield('menu')


@section('page_title', __('page_titles.portal.report.index'))


@section('breadcrumbs')

    <li class="breadcrumb-item"><a href="{{ route('portal.home') }}"> @lang('page_titles.general.home') </a></li>
    <li class="breadcrumb-item"> @lang('page_titles.portal.report.index') </li>    
    <li class="breadcrumb-item active"> @lang('page_titles.portal.process.search') </li>    

@endsection



@section('content')

	<div class="col-12">
		<div class="card">
			<div class="card-body">


				<div class="container">
					<div class="row">
						<div class="col"></div>
						<div class="col-10">

							<h2 class="text-center">Além do período, você pode informar <span class="text-info">{{ $filtrosDisponiveis }}</span> para realizar a busca</h2>
							<h5 class="text-center text-muted m-b-30">@lang('page_titles.portal.logs.warning')</h5>

							@if(Session::has('message'))
								@component('components.alert')
								@endcomponent

								{{ Session::forget('message') }}
							@endif

							<form method="POST" action="{{ route('portal.relatorio.buscar') }}">
								{{ csrf_field() }}
								<input type="hidden" name="idEmpresa" value="{{ $idEmpresa }}">
								<input type="hidden" name="idProcesso" value="{{ $idProcesso }}">
								<input type="hidden" name="idxFiltro" value="{{ $idxFiltro }}">
                                
								<div class="row">
									<div class="col-md-4">
										<div class="form-group">
											@switch($idxFiltro)
												@case(1)
													<label class="control-label">Matrícula</label>
													<input type="text" name="filtro_adicional" class="form-control form-control-lg text-center" value="{{ is_null($filtroAdicional) ? '' : $filtroAdicional }}" placeholder="{{ explode('ou', Constants::$FILTER_OPTIONS[$idxFiltro])[0] }}" maxlength="6">
												@break
													
												@default
													<label class="control-label">CPF</label>
													<input type="text" name="filtro_adicional" class="form-control form-control-lg text-center cpf"  value="{{ is_null($filtroAdicional) ? '' : $filtroAdicional }}" placeholder="Digite o CPF">
												@break
											@endswitch
										</div>
									</div>
									<div class="col-md-8">
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label">Data Início</label>
													<input type="date" name="dataInicio" class="form-control form-control-lg text-center" value="{{ $dataInicio }}" required>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label class="control-label">Data Término</label>
													<input type="date" name="dataTermino" class="form-control form-control-lg text-center" value="{{ $dataTermino }}" required>
												</div>
											</div>
										</div>
									</div>
								</div>
								
								<div class="m-t-20">
									<button type="submit" class="btn waves-effect waves-light btn-lg btn-block btn-success pull-right">@lang('buttons.general.search') </button>
								</div>
							</form>
						</div>
						<div class="col"></div>
					</div>
				</div>

				<div class="table-responsive m-t-40">
					<table id="dataTable-resultado-pesquisa" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th>Data</th>
								<th>Valor</th>
								<th>Processo</th>
								<th>Descrição</th>
								<th>Complemento</th>
								<th>Acesso</th>
							</tr>
						</thead>
						<tbody>
							@forelse ($logs as $log)
								<tr>
									<td>{{ $log->data->format('d/m/Y') }}</td>
									<td>
										<ul class="list-icons">
											@foreach (explode(';', $log->valor) as $property)
												<li><i class="fa fa-chevron-right"></i><?php echo(Helper::stylizeString($property)); ?></li>
											@endforeach
										</ul>
									</td>
									<td>{{ $log->nomeProcesso }}</td>
									<td><?php echo(Helper::stylizeString($log->descricao)); ?></td>
									<td><?php echo(Helper::stylizeString($log->complemento)); ?></td>
									<td> <a href="{{ route('portal.processo.listarDocumentos', ['_idRegistro' => $log->idRegistro]) }}" target="_blank" class="btn waves-effect waves-light btn-secondary m-l-10"> <i class="mdi mdi-file-document"></i> @lang('buttons.general.list_documents') </button> </td>
								</tr>
							@empty
								<tr>
									<td class="text-center" colspan="6">@lang('page_titles.portal.logs.empty')</td>
								</tr>
							@endforelse
						</tbody>
					</table>
				</div>

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
            $('#dataTable-resultado-pesquisa').DataTable({
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
@endsection