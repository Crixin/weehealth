
@extends('layouts.app')

@section('page_title', __('page_titles.core.log.index'))

@section('breadcrumbs')

    <li class="breadcrumb-item"><a href="{{ route('core.home') }}"> @lang('page_titles.general.home') </a></li>
    <li class="breadcrumb-item active"> @lang('page_titles.core.log.index') </li>    

@endsection



@section('content')

    <div class="col-12">
        <div class="card">
            <div class="card-body">

                    @if(Session::has('message'))
                        @component('components.alert')@endcomponent

                        {{ Session::forget('message') }}
                    @endif

                    <form method="POST" action="{{route('core.log')}}" name="createLog" id="createLog"> 
                        {{ csrf_field() }}
                        <div class="col-md-12">
                            
                            <div class="row">
                                <h4><b>FILTROS</b></h4>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group{{ $errors->has('usuario') ? ' has-error' : '' }}">
                                    {!! Form::label('usuario', 'Usuário') !!}
                                    {!! Form::select('usuario[]',$usuarios, $usuarioSelecionado, ['id' => 'usuario', 'class' => 'form-control selectpicker', 'data-live-search' => 'true', 'data-actions-box' => 'true' , 'multiple']) !!}
                                    <small class="text-danger">{{ $errors->first('usuario') }}</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group{{ $errors->has('chave') ? ' has-error' : '' }}">
                                        {!! Form::label('chave', 'Chave') !!}
                                        {!! Form::text('chave', $chaveSelecionado, ['class' => 'form-control']) !!}
                                        <small class="text-danger">{{ $errors->first('chave') }}</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group{{ $errors->has('operacao') ? ' has-error' : '' }}">
                                    {!! Form::label('operacao', 'Opecação') !!}
                                    {!! Form::select('operacao[]',$operacoes, $operacaoSelecionado, ['id' => 'operacao', 'class' => 'form-control selectpicker', 'data-live-search' => 'true', 'data-actions-box' => 'true' , 'multiple']) !!}
                                    <small class="text-danger">{{ $errors->first('operacao') }}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group{{ $errors->has('tipoData') ? ' has-error' : '' }}">
                                        {!! Form::label('tipoData', 'Período') !!}
                                        {!! Form::select('tipoData',$tipoData, $opcoesSelecionado, ['id' => 'tipoData', 'class' => 'form-control selectpicker', 'placeholder' => __('components.selectepicker-default')]) !!}
                                        <small class="text-danger">{{ $errors->first('tipoData') }}</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group{{ $errors->has('dataInicial') ? ' has-error' : '' }}" style="display: none" id="divDataInicial">
                                        {!! Form::label('dataInicial', 'Data Inicial') !!}
                                        <input type="datetime-local" name="dataInicial" id="dataInicial" class="form-control" value="{{$dataInicialSelecionado}}">
                                        
                                        <small class="text-danger">{{ $errors->first('dataInicial') }}</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group{{ $errors->has('dataFinal') ? ' has-error' : '' }}" style="display: none" id="divDataFinal" >
                                        {!! Form::label('dataFinal', 'Data Final') !!}
                                        <input type="datetime-local" name="dataFinal" id="dataFinal" class="form-control" value="{{$dataFinalSelecionado}}">
                                        <small class="text-danger">{{ $errors->first('dataFinal') }}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group{{ $errors->has('tabela') ? ' has-error' : '' }}">
                                    {!! Form::label('tabela', 'Tabela') !!}
                                    {!! Form::select('tabela', $tabelas, $tabelaSelecionada, ['id' => 'tabela', 'class' => 'form-control selectpicker', 'data-live-search' => 'true', 'data-actions-box' => 'true', 'placeholder' => __('components.selectepicker-default') ]) !!}
                                    <small class="text-danger">{{ $errors->first('tabela') }}</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group{{ $errors->has('coluna') ? ' has-error' : '' }}">
                                    {!! Form::label('coluna', 'Coluna') !!}
                                    {!! Form::select('coluna',$colunas, $colunaSelecionada, ['id' => 'coluna', 'class' => 'form-control selectpicker', 'data-live-search' => 'true', 'data-actions-box' => 'true', 'placeholder' => __('components.selectepicker-default')]) !!}
                                    <small class="text-danger">{{ $errors->first('coluna') }}</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-actions ">
                                <button type="submit" class="btn btn-success  pull-right "> <i class="fa fa-search"></i> @lang('buttons.general.search')</button>
                                <a href="{{ route('core.log') }}" class="btn btn-inverse pull-right mr-1 "><i class="fa fa-ban"></i> @lang('buttons.general.clear')</a>
                            </div> 
                            
                            
                        </div>
                    </form>
                    
                    <div class="table-responsive m-t-40">
                        <table id="dataTable-log" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Usuário</th>
                                    <th>Data - Hora</th>
                                    <th>Tabela</th>
                                    <th>Coluna</th>
                                    <th>Operação</th>
                                    <th>Chave</th>
                                    <th>Valor Velho</th>
                                    <th>Valor Novo</th>
                                    <th></th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($logs as $log)
                                    <tr>
                                        <td>{{$log->id}}</td>
                                        <td>{{$log->usuario}}</td>
                                        <td>{{date('d/m/Y h:i:s', strtotime($log->created_at))}}</td>
                                        <td>{{$log->tabela}}</td>
                                        <td>{{$log->coluna}}</td>
                                        <td>{{$log->operacao}}</td>
                                        <td>{{$log->chave}}</td>
                                        <td>{{ Helper::limitChar($log->valor_velho,40)}}</td>
                                        <td>{{Helper::limitChar($log->valor_novo,40)}}</td>
                                        <th></th>
                                    </tr>
                                @endforeach
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
    <script src="{{ asset('js/dataTables/dataTables.responsive.min.js') }}"></script>

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

            if($('#tipoData').val() == 'definir'){
                $('#divDataInicial, #divDataFinal').show();
                $('#dataInicial, #dataFinal').attr('required', true);
            }

            $('#tipoData').on('change', function(){
                if($(this).val() == 'definir'){
                    $('#divDataInicial, #divDataFinal').show();
                    $('#dataInicial, #dataFinal').attr('required', true);
                }else{
                    $('#divDataInicial, #divDataFinal').hide();
                    $('#dataInicial, #dataFinal').attr('required', false);
                }
            });

            $('#dataTable-log').DataTable({
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
                ],
                responsive: {
                    details: {
                        type: 'column',
                        target: -1
                    }
                },
                columnDefs: [ {
                    className: 'control',
                    orderable: false,
                    targets:   -1
                } ]
            });


            $('#tabela').on('change', function(){
                let tabela = $(this).val();
                buscaColuna(tabela);
            });
        });

        function buscaColuna(tabela){
            let obj = {'tabela': tabela};
            ajaxMethod('POST', "{{ URL::route('core.log.tabela') }}", obj).then(response => {
                if(response.response == 'erro') {
                    swal2_alert_error_support("Tivemos um problema ao buscar os campos da tabela.");
                }
                let data = response.data;
                let linha = '';
                $('#coluna').empty();
                linha += '<option value="">Nada selecionado</option>';
                for (let index = 0; index < data.length; index++) {
                    const element = data[index];
                    console.log(element.column_name);
                    linha += '<option value="'+element.column_name+'">'+element.column_name+'</option>'
                }
                console.log(linha);
                $('#coluna').append(linha).selectpicker('refresh');

            }, error => {
                console.log(error);
            });
        }
    </script>
@endsection