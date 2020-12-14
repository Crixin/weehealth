@extends('layouts.app')




@section('page_title', __('page_titles.portal.ged.index'))


@section('breadcrumbs')

<li class="breadcrumb-item"><a href="{{ route('portal.home') }}"> @lang('page_titles.general.home') </a></li>
<li class="breadcrumb-item"> @lang('page_titles.portal.ged.index') </li>

@endsection

@section('content')

<div class="col-12">
	<div class="card">
		<div class="card-body">
			@if(Session::has('message'))
				@component('portal.components.alert') @endcomponent
				{{ Session::forget('message') }}
			@endif

			<div class="container">
				<div class="row">
					<div class="col-10">

						@if ($tipo == "create")
                        	<form method="POST" action="{{ route('portal.ged.salvar') }}" enctype="multipart/form-data">
						@elseif ($tipo == "edit-form")
							<form method="POST" action="{{ route('portal.ged.alterar') }}" >
								<input type="hidden" name="idRegistro" id="idRegistro" value="{{$idRegistro}}"/>
								<input type="hidden" name="idEmpresaProcesso" id="idEmpresaProcesso" value="{{$empresaProcessoSelected}}"/>
						@else
							<form method="POST" action="{{ route('portal.ged.search') }}">
						@endif

							{{ csrf_field() }}
							
							<div class="form-group">
                                <label class="control-label">Processos</label>
                                <select name="empresaProcesso" id="empresaProcesso" class="form-control text-center selectpicker" required data-size="10" data-live-search="true" data-actions-box="true">
									<option value="">Selecione</option>
                                    @foreach ($empresasProcessos as $key => $empresa)
                                        <optgroup value="{{ $empresa->id }}" label="{{$empresa->nome}}">
                                        @foreach ($empresa->portalProcesses as $key => $processo)
                                            <option value="{{ $processo->pivot->id }}" data-processo="{{$processo}}" data-empresa="{{$empresa}}" > {{ $processo->nome }} </option>
                                        @endforeach
                                    @endforeach
                                </select>
							</div>
							
							<div class="col-md-12 row componentForInputs"></div>

							<hr>

							@if ($tipo == "create")
                            	<input type="file" name="arquivo_upload[]" id="input-file-now" class="dropify" data-max-file-size="20M" multiple />
							@endif

							@if ($tipo == "edit-form")
								<button class="btn waves-effect waves-light btn-lg btn-block btn-success pull-right" >@lang('buttons.general.update') </button>
							@elseif ($tipo == "create")
								<button class="btn waves-effect waves-light btn-lg btn-block btn-success pull-right mt-2" >@lang('buttons.general.create') </button>
							@else
								<button class="btn waves-effect waves-light btn-lg btn-block btn-success pull-right mt-2" >@lang('buttons.general.search') </button>
							@endif
						</form>
						
						<div class="m-t-20">
							@if ($tipo == "edit-form")
								<a href="{{ route('portal.ged.list-document', [$empresaProcessoSelected, $idRegistro]) }}" class="btn waves-effect waves-light btn-lg btn-block btn-success pull-right mt-2" >@lang('buttons.portal.ged.view-insert-docs') </a>
								<form method="POST" action="{{ route('portal.ged.search') }}">
									{{ csrf_field() }}
									<button class="btn waves-effect waves-light btn-lg btn-block btn-secondary pull-right mt-4" >@lang('buttons.general.back') </button>
								</form>
							@endif
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection

@section('footer')
    <!-- jQuery file upload -->
    <link rel="stylesheet" href="{{ asset('plugins/dropify/dist/css/dropify.min.css') }}">
    <script src="{{ asset('plugins/dropify/dist/js/dropify.min.js') }}"></script>
    <script>
		$(document).ready(function() {
			$('.dropify').dropify();
		});

		$("#empresaProcesso").change(function (e) { 
            $(".componentForInputs").empty()
            let processo = JSON.parse($(':selected', $(this)).attr("data-processo"))

            let campos = JSON.parse(processo.pivot.todos_filtros_pesquisaveis);

            campos = campos.filter(function(item) {
                return !['criadorRegistro', 'ultimaModificacaoRegistro', 'Data_do_registro'].includes(item.identificador)
            })

			let required = Boolean("{!! ($tipo == 'create') !!}");
			
			let configs = {
				'required': required,
			}


            createFiltersComponentsGED(campos , $(".componentForInputs"), {!! json_encode($tipoIndicesGED, JSON_HEX_TAG) !!}, configs);
		});
		
		if (Boolean("{!! ($tipo == 'edit' || $tipo == 'edit-form') !!}")) {
			let empresaProcesso = "{!! $empresaProcessoSelected ?? '' !!}"
			
			$("#empresaProcesso").val(empresaProcesso)
			$("#empresaProcesso").trigger("change")
			
			if (Boolean("{!! ($tipo == 'edit-form') !!}")) {
				$("#empresaProcesso").attr("disabled", true)

				let indicesRegistro = {!! json_encode($indicesRegistro ?? [] ) !!};
				$.each(indicesRegistro, function (idx, elm) { 
					$("#" + idx).val(elm)
				});
			}
		}
		
    </script>
@endsection