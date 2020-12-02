@extends('layouts.app')

@extends('layouts.menuPortal')
@yield('menu')


@section('page_title', __('page_titles.portal.downloads.index'))


@section('breadcrumbs')

    <li class="breadcrumb-item"><a href="{{ route('portal.home') }}"> @lang('page_titles.general.home') </a></li>    
    <li class="breadcrumb-item active"> @lang('page_titles.portal.downloads.index') </li>    

@endsection



@section('content')

	<div class="col-12">
		<div class="card">
			<div class="card-body">

				<div class="container">
					<div class="row">

                        {{-- Alertas pré-filtro --}}
                        <div class="col-6 m-b-30">
                            <div class="alert alert-info"> @lang('action.messages.filter_1_desc') </div>
                        </div>
                        <div class="col-6 m-b-30">
                            <div class="alert alert-info"> @lang('action.messages.filter_2_desc') </div>    
                        </div>
                        
                        <hr>
                        
                        {{-- Campos de busca [form] --}}
						<div class="col"></div>
						<div class="col-8">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if(Session::has('message'))
                                @component('components.alert')
                                @endcomponent

                                {{ Session::forget('message') }}
                            @endif
                            
                            @if(Session::has('nome_zip') && Session::has('pasta'))
                                <div class="alert alert-success m-b-20 m-t-5">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                    <h3 class="text-success"><i class="fa fa-check-circle"></i> Arquivo sendo criado...</h3> 
                                    O arquivo <span class="font-weight-bold">{{ Session::get('nome_zip') }}</span> está sendo gerado... Quando o processamento terminar, você poderá encontrá-lo na pasta <span class="font-weight-bold">{{ Session::get('pasta') }}</span> do FTP.
                                </div>
                                    
                                <div class="col-md-12 m-b-40">
                                    <div class="input-group">
                                        <input type="text" id="fullPath" class="form-control" value="ftp://{{ Session::get('ftpBasePath') . Session::get('pasta') . Session::get('nome_zip') }}" readonly>
                                        <span class="input-group-btn"><button class="btn btn-info" type="button" onclick="copyToClipboard()"><i class="fa fa-copy"></i> Copiar</button></span>
                                    </div>
                                </div>
                                @endif

                            <form method="POST" action="{{ route('portal.download.criarZip') }}">
                                {{ csrf_field() }}

								<h2 class="text-center">Informe <span class="text-muted">o CPF</span> e/ou <span class="text-muted">a Matrícula</span> para buscar</h2>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">Matrícula</label>
                                                    <input type="text" name="matricula" class="form-control form-control-lg text-center" maxlength="6">
                                                    <small class="form-control-feedback text-info"> @lang('action.messages.filter_1') </small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="control-label">CPF</label>
                                                    <input type="text" name="cpf" class="form-control form-control-lg text-center cpf">
                                                    <small class="form-control-feedback text-info"> @lang('action.messages.filter_2') </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label">Empresa</label>
                                            <select name="empresa_id" id="empresa_id" class="form-control form-control-lg text-center select2" required>
                                                @foreach ($empresas as $key => $empresa)
                                                    <option value="{{ $key }}"> {{ $empresa }} </option>
                                                @endforeach
                                            </select>
                                            <small class="form-control-feedback"> Serão listadas aqui apenas as empresas que preencheram o campo 'Pasta FTP'. </small> 
                                        </div>
                                    </div>
                                </div>
								<div class="m-t-20">
                                    <div class="alert alert-warning">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                        <h3 class="text-warning"><i class="fa fa-exclamation-triangle"></i> @lang('action.warning') </h3> @lang('action.messages.download_delay')
                                    </div>
                                    
                                    <button type="submit" class="btn waves-effect waves-light btn-lg btn-block btn-success pull-right"> @lang('buttons.general.search') </button>
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

    {{-- Select 2 --}}
    <link href="{{ asset('plugins/select2/dist/css/select2.min.css') }}" rel="stylesheet" type="text/css">    
    <script src="{{ asset('plugins/select2/dist/js/select2.full.min.js') }}" type="text/javascript"></script>
    <script>
        $(".select2").select2();
    </script>
    <style>
		span.select2-selection {
			min-height: 48px;
			font-size: large;
			padding-top: 7px;
		}
    </style>


    {{-- Função para copiar o caminho completo do FTP para a área de transferência | Snippet from: https://www.w3schools.com/howto/howto_js_copy_clipboard.asp --}}
    <script>
        function copyToClipboard() {
            /* Get the text field */
            var copyText = document.getElementById("fullPath");

            /* Select the text field */
            copyText.select(); 
            copyText.setSelectionRange(0, 99999); /*For mobile devices*/

            /* Copy the text inside the text field */
            document.execCommand("copy");

            /* Alert the copied text => copyText.value */
            showToast('Texto copiado!', 'O valor já está na área de transferência.', 'success');
        }
    </script>

@endsection 