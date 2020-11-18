@extends('core::layouts.app')

@extends('core::layouts.menuDocs')
@yield('menu')

@section('content')
    <!-- O que fazer nesta situação? -->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    
    <link href="{{ asset('plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}"  rel="stylesheet">
    <script src="{{ asset('plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js') }}"></script>




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
                        <li class="breadcrumb-item"><a href="{{ URL::route('documentacao') }}">Documentação</a></li>
                        <li class="breadcrumb-item active">Documentos Pendentes de Revisão</li>
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
                            
                            <ul class="nav nav-tabs nav-fill customtab" role="tablist">
                                <li class="nav-item"> <a class="nav-link font-bold active" data-toggle="tab" href="#docsObsoletos" role="tab"><h3 class="hidden-xs-down">DOCUMENTOS PENDENTES REVISÃO</h3></a> </li>
                            </ul>
                            
                            <div class="tab-content">
                                <div class="tab-pane active" id="docsObsoletos" role="tabpanel">
                                    <div class="p-20">
                                        <div class="col-md-12">
                                            <div class="row">
                                                <h4>FILTROS</h4>
                                                
                                                <div class="col-md-12">
                                                    {!! Form::open(['route' => 'documentacao.filter_pendentes_revisao', 'class' => 'form-horizontal']) !!}
                                                        <div class="row margin-top-1percent">    
                                                            <div class="col-md-6"> 
                                                                <div class="row">
                                                                    {!! Form::select('search_setor', array(null => '- Setor -') + $setores, null, ['class' => 'form-control  custom-select']) !!}
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <a href="{{ route('documentacao.pendentes_revisao') }}" class="btn btn-block waves-effect waves-light btn-secondary"><i class="fa fa-ban"></i> Limpar</a>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <button type="submit" class="btn btn-block waves-effect waves-light btn-outline-success"><i class="fa fa-search"></i> Buscar</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>                                                          
                                                    {!! Form::close() !!}     
                                                </div>
                                                
                                                @if( !empty($errorMessage) )
                                                    <div class="col-md-12 alert alert-danger m-t-20"> {{ $errorMessage }}</div>
                                                @endif

                                            </div>

                                            <div class="row mt-5 margin-top-1percent">
                                                <div class="table-responsive">
                                                    <table id="docs-pendentes-revisao" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-center text-nowrap">Ações</th>
                                                                <th class="text-center text-nowrap">Código</th>
                                                                <th class="text-center">Título do Documento</th>
                                                                <th class="text-center">Última Revisão</th>
                                                                <th class="text-center text-nowrap ">Revisão</th>

                                                                <th class="text-center">Status</th>
                                                                <th class="text-center">Nível Acesso</th>
                                                                <th class="text-center">Data Emissão Inicial</th>
                                                                <th class="text-center">Validade</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @if( $documentos_vencidos != null && count($documentos_vencidos) > 0 )
                                                            
                                                                @foreach($documentos_vencidos as $docVenc)
                                                                    <tr>
                                                                        <td class="text-nowrap text-center">
                                                                            @if( Auth::user()->setor_id == Constants::$ID_SETOR_QUALIDADE )
                                                                                <a href="{{ route('documentacao.presence-lists', ['id' => $docVenc->id]) }}" class="mr-3"> <i class="fa fa-file-text-o text-primary" data-toggle="tooltip" data-original-title="Ver Listas de Presença"></i> </a>     
                                                                            @endif

                                                                            @if( Auth::user()->permissao_elaborador )
                                                                                <a href="javascript:void(0)" class="btn-open-confirm-review" data-id="{{ $docVenc->id }}"> <i class="fa fa-eye text-warning" data-toggle="tooltip" data-original-title="Iniciar Revisão"></i> </a>
                                                                            @endif
                                                                        </td>

                                                                        <td class="text-center text-nowrap"> {{ $docVenc->codigo }} </td>

                                                                        {{ Form::open(['route' => 'documentacao.view-document', 'method' => 'POST', 'id'=>'view-documento-'.$docVenc->id]) }}
                                                                            {{ Form::hidden('document_id', $docVenc->id) }}

                                                                            <td class="text-center a-href-submit force-break-word text-nowrap">
                                                                                <p class="text-center a-href-submit force-break-word submit-view-documento" data-id="{{ $docVenc->id }}"> {{ explode(Constants::$SUFIXO_REVISAO_NOS_TITULO_DOCUMENTOS, $docVenc->nome)[0] }}</p>
                                                                            </td>
                                                                        {{ Form::close() }}

                                                                        
                                                                        <td class="text-center">{{ ($docVenc->data_revisao) ?  date("d/m/Y H:i:s", strtotime($docVenc->data_revisao)) :  '-' }}</td>

                                                                        <td class="text-nowrap text-center"> {{ $docVenc->revisao }} </td>

                                                                        
                                                                        <td class="text-center"><p class="font-weight-bold text-warning"> Pend. Revisão </p></td>

                                                                        <td class="text-center">{{ $docVenc->nivel_acesso }}</td>

                                                                        <td class="text-center">{{ date("d/m/Y H:i:s", strtotime($docVenc->created_at)) }}</td>

                                                                        <td class="text-center">{{ date("d/m/Y", strtotime($docVenc->validade)) }}</td>
                                                                    </tr>
                                                                @endforeach

                                                            @else
                                                                <tr>
                                                                    <td colspan="10" class="text-center"> Não foram encontrados documentos pendentes de revisão! </td>
                                                                </tr>
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            
                                        </div> 
                                    </div>
                                </div>
                            </div>

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


    <!-- Modal de confirmação - deseja mesmo iniciar uma revisão para este documento -->
    <div class="modal fade bs-example-modal-sm" id="iniciar-revisao-modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-body"> 
                    Deseja iniciar uma revisão para este documento? 
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Não</button>

                    {{ Form::open(['route' => 'documentacao.start-review', 'method' => 'POST']) }}
                        {{ Form::hidden('document_id', '', ['id' => 'document_id_request_review']) }}
                        <button type="submit" class="btn btn-success waves-effect"> Sim </button>
                    {!! Form::close() !!}
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.Modal de confirmação - deseja mesmo iniciar uma revisão para este documento -->


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
            $('#docs-pendentes-revisao').DataTable({
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

        // Ao clicar no botão que abrirá o modal de confirmação para revisão do documento
        $(".btn-open-confirm-review").click(function(){
            var id = $(this).data('id');
            $("#document_id_request_review").val(id);
            
            $("#iniciar-revisao-modal").modal({
                backdrop: 'static',
                keyboard: false
            });
        });

        // Ao clicar no nome do documento, redireciona para a tela de visualização
        $(document).on("click", ".submit-view-documento", (elm) => {
            let id = $(elm.target).attr('data-id');
            $(`#view-documento-${id}`)[0].submit();
        });

    </script>

@endsection
