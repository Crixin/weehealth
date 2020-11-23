@extends('layouts.app')

@extends('layouts.menuPortal')
@yield('menu')


@section('page_title', __('page_titles.portal.process.index'))


@section('breadcrumbs')

<li class="breadcrumb-item"><a href="{{ route('portal.home') }}"> @lang('page_titles.general.home') </a></li>
<li class="breadcrumb-item"> @lang('page_titles.portal.process.index') </li>
<li class="breadcrumb-item active"> @lang('page_titles.portal.process.search') </li>

@endsection



@section('content')

<div class="col-12">
	<div class="card">
		<div class="card-body">
			@if(Session::has('message'))
				@component('componentes.alert') @endcomponent
				{{ Session::forget('message') }}
			@endif

			<div class="container">
				<blockquote>
					<a href="javascript:void(0)" class="cursor-default" style="font-size: large;"> @lang('home.permissions_level'): 
						<span class="font-weight-bold">{{ (Auth::user()->utilizar_permissoes_nivel_usuario) ? 'USUÁRIO' : 'GRUPO'  }}</span> 
					</a>

					<div class="row">
						@if (Auth::user()->utilizar_permissoes_nivel_usuario)
							@foreach (Helper::getUserEnterprises() as $empresa)
								<div class="col-md-6 m-b-5">
									<i class="fa fa-check text-success"></i> Vinculado à empresa: <span class="font-weight-bold">{{ $empresa->nome }}</span>
								</div>
							@endforeach
						@else
							@foreach (Helper::getUserGroups() as $grupo)
								<div class="col-md-6 m-b-5 m-t-5">
									<i class="fa fa-check text-success"></i> Vinculado ao grupo: <span class="font-weight-bold">{{ $grupo->nome }}</span>
								</div>
							@endforeach
						@endif
					</div>
				</blockquote>

				<hr>

				<div class="row">
					<div class="col"></div>
					<div class="col-10">
						<form method="POST" action="{{ route('portal.processo.listarRegistros') }}">
							{{ csrf_field() }}

							<input type="hidden" name="componentsForSubmit" id="componentsForSubmit" />

							<div class="col-md-12 row componentForInputs"></div>

							<div class="m-t-20">
								<button type="button"
									class="btn waves-effect waves-light btn-lg btn-block btn-success pull-right"
									onclick="submitForm();">@lang('buttons.general.search') </button>
							</div>
						</form>
					</div>
					<div class="col"></div>
				</div>
			</div>

		</div>
	</div>
</div>

@endsection

@section('footer')

<script>
	let configs = { 
		"valoresPreDefinidos" : {!! $filtros !!},
		"disabled" : true,
	}
	createFiltersComponentsGED({!! json_encode($indices, JSON_HEX_TAG) !!}, $(".componentForInputs"), {!! json_encode($tipoIndicesGED, JSON_HEX_TAG) !!}, configs );

	function submitForm(){
			
		let componentsForSubmit = []; 

		$.each($(".submitComponent"), function(i, e){            
			if ($(this).val()) {
				componentsForSubmit.push({
					idTipoIndice: $(this).data("indice"),
						identificador: $(this).data("identificador"),
						valor: $(this).val()
				});
			}
		});     

		$("#componentsForSubmit").val(JSON.stringify(componentsForSubmit));
		$("form").submit();
	}
        
</script>
@endsection