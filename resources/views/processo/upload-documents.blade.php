@extends('app')


@section('page_title', __('page_titles.process.index'))


@section('breadcrumbs')

    <li class="breadcrumb-item"><a href="{{ route('home') }}"> @lang('page_titles.general.home') </a></li>
    <li class="breadcrumb-item"> @lang('page_titles.process.index') </li>    
    <li class="breadcrumb-item active"> @lang('page_titles.process.upload') </li>    

@endsection



@section('content')

	<div class="col-12">
		<div class="card">
			<div class="card-body">

				<div class="container">
					<blockquote>
						<a href="javascript:void(0)" class="cursor-default" style="font-size: large;"> @lang('home.permissions_level'): <span class="font-weight-bold">{{ (Auth::user()->utilizar_permissoes_nivel_usuario) ? 'USUÁRIO' : 'GRUPO'  }}</span> </a>

						<div class="row">
							@if (Auth::user()->utilizar_permissoes_nivel_usuario)
								@foreach (Helper::getUserEnterprises() as $empresa)
									<div class="col-md-6 m-b-5"><i class="fa fa-check text-success"></i> Vinculado à empresa: <span class="font-weight-bold">{{ $empresa->nome }}</span></div>
								@endforeach                               
							@else
								@foreach (Helper::getUserGroups() as $grupo)
									<div class="col-md-6 m-b-5 m-t-5"><i class="fa fa-check text-success"></i> Vinculado ao grupo: <span class="font-weight-bold">{{ $grupo->nome }}</span></div>
								@endforeach                             
							@endif
						</div>
					</blockquote>

					<hr>

					<div class="row">
						<div class="col"></div>
						<div class="col-8">

							@if(Session::has('message'))
								@component('componentes.alert')
								@endcomponent
			
								{{ Session::forget('message') }}
							@endif

							<form method="POST" action="{{ route('processo.realizarUpload') }}" enctype="multipart/form-data">
								{{ csrf_field() }}
								<input type="hidden" name="idAreaGED" value="{{ $idAreaGED }}">

								<h2 class="text-center">Informe <span class="text-info">o CPF</span> e selecione o arquivo</h2>
								
								<div class="row m-t-10">
									<input type="text" name="cpf" class="form-control form-control-lg text-center cpf" placeholder="Digite apenas números" required>
								</div>
								
								<div class="row m-t-30">
									<input type="file" name="arquivo_upload" id="input-file-now" class="dropify" data-max-file-size="10M" required />
									<button type="submit" class="btn waves-effect waves-light btn-lg btn-block btn-success pull-right m-t-20"><i class="mdi mdi-file-export"></i> @lang('buttons.general.upload') </button>
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
	
	<!-- jQuery file upload -->
	<link rel="stylesheet" href="{{ asset('plugins/dropify/dist/css/dropify.min.css') }}">
    <script src="{{ asset('plugins/dropify/dist/js/dropify.min.js') }}""></script>
    <script>
		$(document).ready(function() {
			// Basic
			$('.dropify').dropify();
		});
	</script>

	<style>
		.dropify-message p {
			text-align: center!important;
		}

		span.select2-selection {
			min-height: 48px;
			font-size: large;
			padding-top: 7px;
		}
	</style>

@endsection
