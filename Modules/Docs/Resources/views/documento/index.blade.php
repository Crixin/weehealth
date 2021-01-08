@extends('layouts.app')




@section('page_title', __('page_titles.docs.documento.index'))


@section('breadcrumbs')

    <li class="breadcrumb-item"><a href="{{ route('docs.home') }}"> @lang('page_titles.general.home') </a></li>
    <li class="breadcrumb-item active"> @lang('page_titles.docs.documento.index') </li>    

@endsection



@section('content')

    <div class="col-12">
        <div class="card">
            <div class="card-body">

                    @if(Session::has('message'))
                        @component('components.alert')@endcomponent

                        {{ Session::forget('message') }}
                    @endif

                    <form method="POST" action="{{route('docs.documento')}}" name="createDocumento" id="createDocumento"> 
                        {{ csrf_field() }}
                        <div class="col-md-12">
                            {{-- Aviso: prioridade do título do documento no filtro --}}
                            <!--<div class="row">
                                <h5 class="alert alert-info alert-dismissible" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    Quando o campo <b>Título do Documento</b> for preenchido, os outros filtros serão <b>ignorados</b>. Caso o campo seja deixado em branco, os outros filtros serão aplicados em conjunto.
                                </h5>
                            </div>-->
                            <div class="row">
                                <h4><b>FILTROS</b></h4>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group{{ $errors->has('titulo') ? ' has-error' : '' }}">
                                        {!! Form::label('titulo', 'Título') !!}
                                        {!! Form::text('titulo', $tituloSelecionado, ['class' => 'form-control']) !!}
                                        <small class="text-danger">{{ $errors->first('titulo') }}</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group{{ $errors->has('setor') ? ' has-error' : '' }}">
                                        {!! Form::label('setor', 'Setor') !!}
                                        {!! Form::select('setor[]',$setores, $setorSelecionado, ['id' => 'setor', 'class' => 'form-control selectpicker', 'data-live-search' => 'true', 'data-actions-box' => 'true' , 'multiple']) !!}
                                        <small class="text-danger">{{ $errors->first('setor') }}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group {{ $errors->has('tipoDocumento') ? ' has-error' : '' }}">
                                        {!! Form::label('tipoDocumento', 'Tipo de Documento' ) !!}
                                        {!! Form::select('tipoDocumento[]',$tiposDocumento, $tipoDocumentoSelecionado, ['id' => 'tipoDocumento', 'class' => 'form-control selectpicker',  'data-live-search' => 'true', 'data-actions-box' => 'true','multiple']) !!}
                                        <small class="text-danger">{{ $errors->first('tipoDocumento') }}</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
                                        {!! Form::label('status', 'Status do Documento(NÃO FUNCIONA VER)') !!}
                                        {!! Form::select('status[]',$status, $statusSelecionado, ['id' => 'status', 'class' => 'form-control selectpicker' , 'data-live-search' => 'true', 'data-actions-box' => 'true' , 'multiple']) !!}
                                        <small class="text-danger">{{ $errors->first('status') }}</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group{{ $errors->has('nivelAcesso') ? ' has-error' : '' }}">
                                    {!! Form::label('nivelAcesso', 'Nível de Acesso') !!}
                                    {!! Form::select('nivelAcesso[]',$niveisAcesso, $niveisSelecionado, ['id' => 'nivelAcesso', 'class' => 'form-control selectpicker' , 'data-live-search' => 'true', 'data-actions-box' => 'true', 'multiple']) !!}
                                    <small class="text-danger">{{ $errors->first('nivelAcesso') }}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group{{ $errors->has('tipoVencimento') ? ' has-error' : '' }}">
                                        {!! Form::label('tipoVencimento', 'Vencimento') !!}
                                        {!! Form::select('tipoVencimento',$opcoesVencimento, $opcoesSelecionado, ['id' => 'tipoVencimento', 'class' => 'form-control selectpicker', 'placeholder' => __('components.selectepicker-default')]) !!}
                                        <small class="text-danger">{{ $errors->first('tipoVencimento') }}</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group{{ $errors->has('dataInicial') ? ' has-error' : '' }}" style="display: none" id="divDataInicial">
                                        {!! Form::label('dataInicial', 'Data Inicial') !!}
                                        {!! Form::date('dataInicial', $dataInicialSelecionado, ['class' => 'form-control']) !!}
                                        <small class="text-danger">{{ $errors->first('dataInicial') }}</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group{{ $errors->has('dataFinal') ? ' has-error' : '' }}" style="display: none" id="divDataFinal" >
                                        {!! Form::label('dataFinal', 'Data Final') !!}
                                        {!! Form::date('dataFinal', $dataFinalSelecionado, ['class' => 'form-control']) !!}
                                        <small class="text-danger">{{ $errors->first('dataFinal') }}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">

                                    <div class="form-group{{ $errors->has('copiaControlada') ? ' has-error' : '' }}">
                                        {!! Form::label('copiaControlada', 'Cópia Controlada') !!}
                                        {!! Form::select('copiaControlada', $options, $copiaControladaSelecionado, ['id' => 'copiaControlada', 'class' => 'form-control selectpicker' , 'placeholder' => __('components.selectepicker-default')]) !!}
                                        <small class="text-danger">{{ $errors->first('copiaControlada') }}</small>
                                    </div>
                                    
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group{{ $errors->has('obsoleto') ? ' has-error' : '' }}">
                                        {!! Form::label('obsoleto', 'Doc. Obsoleto') !!}
                                        {!! Form::select('obsoleto', $options, $obsoletoSelecionado, ['id' => 'obsoleto', 'class' => 'form-control selectpicker', 'placeholder' => __('components.selectepicker-default')]) !!}
                                        <small class="text-danger">{{ $errors->first('obsoleto') }}</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-actions ">
                                <button type="submit" class="btn btn-success  pull-right "> <i class="fa fa-search"></i> @lang('buttons.general.search')</button>
                                <a href="{{ route('docs.documento') }}" class="btn btn-inverse pull-right mr-1 "><i class="fa fa-ban"></i> @lang('buttons.general.clear')</a>
                                <a href="{{ route('docs.documento.novo') }}" class=" pull-right mr-1 btn waves-effect waves-light btn-success pull-right"><i class="fa fa-pencil"></i>&nbsp; @lang('buttons.docs.documento.create') </a>
                            </div> 
                            {{-- Aviso: pesquisa com datatable --}}
                            
                            
                            <div class="row mt-5 margin-top-1percent">
                                
                                <!--<h5 class="alert alert-warning alert-dismissible" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    Para filtrar registros através do <b>Título do Documento</b>, utilize o campo acima. Qualquer outro campo pode ser pesquisado no campo <i>Pesquisar</i> (canto superior direito da tabela).
                                </h5>-->
                            </div>
                            
                        </div>
                    </form>
                    
                    
                
                    <div class="table-responsive m-t-40">
                        <table id="dataTable-documento" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Título do Documento</th>
                                    <th>Vencimento</th>
                                    <th>Revisão</th>
                                    <th>Status</th>
                                    <th>Nível Acesso</th>
                                   
                                    <th>Controle</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($documentos as $documento)
                                    <tr>
                                        <td>{{ $documento->codigo }}</td>
                                        <td>{{ $documento->nome }}</td>
                                        <td>{{ $documento->vencimento }}</td>
                                        <td>{{ $documento->revisao }}</td>
                                        <td>{{ ''}}</td>
                                        <td>{{ $documento->docsNivelAcesso($documento->nivel_acesso_id) }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-block btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> @lang('buttons.general.actions') </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="{{ route('docs.documento.editar', ['id' => $documento->id]) }}"> <i class="fa fa-pencil text-success"></i>&nbsp; @lang('buttons.docs.documento.edit') </a>
                                                    
                                                    <a class="dropdown-item documento-iniciar-validacao" data-id="{{$documento->id}}" href="javascript:void(0)"> <i class="fa fa-eye text-danger"></i>&nbsp; @lang('buttons.docs.documento.start-validation') </a> 
                                                    <a class="dropdown-item" href="{{ route('docs.documento.imprimir', ['id' => $documento->id, 'tipo' => 2]) }}"> <i class="fa fa-print text-info"></i>&nbsp; @lang('buttons.docs.documento.printer') </a> 
                                                    <a class="dropdown-item" href="{{ route('docs.documento.lista-presenca', ['id' => $documento->id]) }}"> <i class="fa fa-file-text-o text-info"></i>&nbsp; @lang('buttons.docs.documento.list') </a> 
                                                    <!--<a class="dropdown-item" href="{{ route('docs.documento.editar', ['id' => $documento->id]) }}"> <i class="fa fa-exchange text-info"></i>&nbsp; @lang('buttons.docs.documento.link-docs') </a>-->
                                                    <a class="dropdown-item documento-iniciar-revisao" data-id="{{$documento->id}}" href="javascript:void(0)"> <i class="fa fa-eye text-warning"></i>&nbsp; @lang('buttons.docs.documento.start-review') </a> 
                                                    <a class="dropdown-item documento-obsoleto" data-id="{{$documento->id}}" href="javascript:void(0)"> <i class="fa fa-power-off text-danger"></i>&nbsp; @lang('buttons.docs.documento.obsolete') </a>                                                 
                                                </div>
                                            </div>
                                        </td>
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
            if($('#tipoVencimento').val() == 'definir'){
                $('#divDataInicial, #divDataFinal').show();
                $('#dataInicial, #dataFinal').attr('required', true);
            }

            $('#tipoVencimento').on('change', function(){
                if($(this).val() == 'definir'){
                    $('#divDataInicial, #divDataFinal').show();
                    $('#dataInicial, #dataFinal').attr('required', true);
                }else{
                    $('#divDataInicial, #divDataFinal').hide();
                    $('#dataInicial, #dataFinal').attr('required', false);
                }
            });

            $('#dataTable-documento').DataTable({
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
        
        // Exclusão do documento
        $('.sa-warning').click(function(){
            let id = $(this).data('id');
            let deleteIt = swal2_warning("Essa ação é irreversível!");
            let obj = {'id': id};

            deleteIt.then(resolvedValue => {
                // ajaxMethod('POST', "/plano/" + idPlano, {}).then(response => {
                ajaxMethod('POST', "{{ URL::route('docs.documento.deletar') }}", obj).then(response => {
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

        //Iniciar Validacao
        $('.documento-iniciar-validacao').click(function(){
            let id = $(this).data('id');
            
            let iniciarValicao = swal2_warning("Que deseja iniciar a validação do documento!", "Sim Iniciar!");
            let obj = {'id': id};
            iniciarValicao.then(resolvedValue => {
                console.log('iniciar validacao');
                ajaxMethod('POST', "{{ URL::route('docs.documento.iniciar-validacao') }}", obj).then(response => {
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

        //Iniciar Revisao
        $('.documento-iniciar-revisao').click(function(){
            let id = $(this).data('id');
            let iniciarRevisao = swal2_warning("Que deseja iniciar a revisão do documento!", "Sim Iniciar!");
            let obj = {'id': id};
            iniciarRevisao.then(resolvedValue => {
                
                ajaxMethod('POST', "{{ URL::route('docs.documento.iniciar-revisao') }}", obj).then(response => {
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

        //Tornar Obsoleto
        $('.documento-obsoleto').click(function(){
            let id = $(this).data('id');
            let tornarObsoleto = swal2_warning("Que deseja tornar obsoleto o documento!", "Sim!");
            let obj = {'id': id};
            tornarObsoleto.then(resolvedValue => {
                
                ajaxMethod('POST', "{{ URL::route('docs.documento.obsoleto') }}", obj).then(response => {
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