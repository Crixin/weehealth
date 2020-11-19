@extends('layouts.app')

@extends('layouts.menuDocs')
@yield('menu')

@section('content')

    <!-- Page wrapper  -->
    <!-- ============================================================== -->
    <div class="page-wrapper">
        <!-- ============================================================== -->
        <!-- Container fluid  -->
        <!-- ============================================================== -->
        <div class="container-fluid">
            

            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <div class="row page-titles">
                <div class="col-md-5 col-8 align-self-center">
                    <h3 class="text-themecolor m-b-0 m-t-0">Documentação</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ URL::route('home') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Relatório Estatístico</li>
                    </ol>
                </div>
                <div class="col-md-7 col-4 align-self-center">
                    <div class="">
                        <button class="right-side-toggle waves-light btn-success btn btn-circle btn-xl pull-right m-l-10  btn-badge badge-top-right" data-count="{{ count(\App\Classes\Helpers::instance()->getNotifications( Auth::user()->id )) }}">
                            <i class="ti-comment-alt text-white"></i>
                        </button>
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            
            
            <!-- ============================================================== -->
            <!-- Start Page Content -->
            <!-- ============================================================== -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            
                            <div class="alert alert-info text-center">Por favor, faça a busca para que possamos encontrar os resultados que deseja!</div>

                            @if($errors->any())
                                <div class="alert alert-danger text-center">{{ $errors->first() }}</div>
                            @endif
                            
                            {{-- Formulário de Filtro: define o período e os tipos de documentos selecionados --}}
                            {!! Form::open(['route' => 'documentacao.make-statical-report', 'method' => 'POST', 'class' => 'form-inline m-t-20']) !!}
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="col-md-12 control-label font-bold">
                                            <h5 class="box-title">Período da Busca</h5>
                                        </div>
                                        <div class="col-md-12">
                                            <input class="form-control input-daterange-datepicker" type="text" name="daterange" value="{{ \Carbon\Carbon::now()->subDays(7)->format('d/m/Y') }} - {{ \Carbon\Carbon::now()->format('d/m/Y') }}" style="width: 100%" required/> 
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="col-md-12 control-label font-bold">
                                            <h5 class="box-title">Tipo de Documento</h5>
                                        </div>
                                        <div class="col-md-12">
                                            {!! Form::select('tipo_documento',  array('todos' => '-- Todos --')  + $tipoDocumentos, null, ['class' => 'form-control  custom-select', 'style' => 'width: 100%', 'required' => 'required']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="col-md-12 control-label font-bold">
                                            <h5 class="box-title">Setores</h5>
                                        </div>
                                        <div class="col-md-12">
                                            <select class="form-control selectpicker" data-size="10" data-live-search="true" data-actions-box="true" name="setores[]" id="setores" multiple>
                                                @foreach($setores as $key => $setor)
                                                    <option value="{{$setor['id']}}">{{$setor['nome']}} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <button class="btn btn-block btn-success m-t-30"> <i class="mdi mdi-search"></i> Buscar</button>
                                </div>
                            {!! Form::close() !!}

                        </div>
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- End Page Content -->
            <!-- ============================================================== -->

        </div>
        <!-- ============================================================== -->
        <!-- End Container fluid -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Page wrapper -->
    <!-- ============================================================== -->

@endsection


@section('footer')

    {{-- DateRangePicker.js --}}
    <link href="{{ asset('plugins/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet" type="text/css" />

    <script src="{{ asset('plugins/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap-select/bootstrap-select.min.js') }}"></script>


    <script>
        $('.input-daterange-datepicker').daterangepicker({
            buttonClasses: ['btn', 'btn-sm'],
            applyClass: 'btn-success',
            cancelClass: 'btn-inverse',
            "locale": {
                "format": "DD/MM/YYYY",
                "separator": " - ",
                "applyLabel": "Selecionar",
                "cancelLabel": "Cancelar",
                "fromLabel": "De",
                "toLabel": "Até",
                "customRangeLabel": "Custom",
                "daysOfWeek": [ "Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sáb" ],
                "monthNames": [ "Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro" ],
                "firstDay": 1
            }
        });
    </script>

@endsection
