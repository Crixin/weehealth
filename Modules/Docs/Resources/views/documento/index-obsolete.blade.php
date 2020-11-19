@extends('layouts.app')

@extends('layouts.menuDocs')
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
                        <li class="breadcrumb-item active">Documentos Obsoletos</li>
                    </ol>
                </div>
                <div class="col-md-7 col-4 align-self-center">
                    <div class="">
                        <button class="right-side-toggle waves-light btn-success btn btn-circle btn-xl pull-right m-l-10   btn-badge badge-top-right" data-count="{{ count(\App\Classes\Helpers::instance()->getNotifications( Auth::user()->id )) }}">
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
                            
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs nav-fill customtab" role="tablist">
                                <li class="nav-item"> <a class="nav-link font-bold active" data-toggle="tab" href="#docsObsoletos" role="tab"><h3 class="hidden-xs-down">DOCUMENTOS OBSOLETOS</h3></a> </li>
                            </ul>
                            <!-- Tab panes -->
                            <div class="tab-content">

                                <!-- 
                                    /* TAB - Visualizar Documento */ 
                                -->
                                <div class="tab-pane active" id="docsObsoletos" role="tabpanel">
                                    <div class="p-20">
                                        <div class="col-md-12">
                                            <div class="row mt-5 margin-top-1percent">
                                                <div class="table-responsive">
                                                    <table class="table" id="obsolet-table">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-center text-nowrap">Ação</th>
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
                                                            @if( $documentos_finalizados != null && count($documentos_finalizados) > 0 )
                                                            
                                                                @foreach($documentos_finalizados as $docF)
                                                                    <tr>
                                                                        <td class="text-nowrap text-center">
                                                                            @if( Auth::user()->setor_id == Constants::$ID_SETOR_QUALIDADE )
                                                                                <a href="{{ route('documentacao.presence-lists', ['id' => $docF->id]) }}" class="mr-3"> <i class="fa fa-file-text-o text-primary" data-toggle="tooltip" data-original-title="Ver Listas de Presença"></i> </a>     
                                                                            @endif

                                                                            <a href="javascript:void(0)" class="btn-ativar-documento-modal ml-2" data-id="{{ $docF->id }}"> <i class="fa fa-power-off text-success" data-toggle="tooltip" data-original-title="Ativar Documento"></i> </a> 
                                                                        </td>

                                                                        <td class="text-center text-nowrap"> {{ $docF->codigo }} </td>

                                                                        {{ Form::open(['route' => 'documentacao.view-obsolete-doc', 'method' => 'POST', 'id'=>'view-documento-'.$docF->id]) }}
                                                                            {{ Form::hidden('document_id', $docF->id) }}
                                                                            <td class="text-center a-href-submit force-break-word text-nowrap">
                                                                                <p class="text-center a-href-submit force-break-word submit-view-documento" data-id="{{ $docF->id }}"> {{ explode(Constants::$SUFIXO_REVISAO_NOS_TITULO_DOCUMENTOS, $docF->nome)[0] }}</p>
                                                                            </td>
                                                                        {{ Form::close() }}

                                                                        
                                                                        <td class="text-center">{{ date("d/m/Y H:i:s", strtotime($docF->updated_at)) }}</td>

                                                                        <td class="text-nowrap text-center"> {{ $docF->revisao }} </td>

                                                                        
                                                                        <td class="text-center"><p class="font-weight-bold text-danger"> Obsoleto </p></td>

                                                                        <td class="text-center">{{ $docF->nivel_acesso }}</td>

                                                                        <td class="text-center">{{ date("d/m/Y H:i:s", strtotime($docF->created_at)) }}</td>

                                                                        <td class="text-center">{{ date("d/m/Y", strtotime($docF->validade)) }}</td>
                                                                    </tr>
                                                                @endforeach

                                                            @else
                                                                <tr>
                                                                    <td colspan="10" class="text-center"> Não foram encontrados documentos obsoletos! </td>
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

            <!-- Modal de confirmação - deseja mesmo ativar o documento -->
            <div class="modal fade" id="ativar-documento-modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" style="display: none;">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-body"> 
                            Tem certeza que deseja ativar o documento ? 
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Não</button>

                            {{ Form::open(['route' => 'documentacao.make-active-doc', 'method' => 'POST']) }}
                                {{ Form::hidden('doc_id', '', ['id' => 'form_id_make_active_doc']) }}
                                <button type="submit" class="btn btn-success waves-effect"> Sim </button>
                            {!! Form::close() !!}
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!-- /.Modal de confirmação - deseja mesmo ativar o documento -->


            <script>
                // Ao clicar no botão que abrirá o modal de confirmação para ativar o formulário 
                $(".btn-ativar-documento-modal").click(function(){
                    var id = $(this).data('id');
                    $("#form_id_make_active_doc").val(id);
                    
                    $("#ativar-documento-modal").modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                });
            </script>

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
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    
    <script type="text/javascript">
        
        $('#obsolet-table').DataTable({ 
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

        $(document).on("click", ".submit-view-documento", (elm) => {
            let id = $(elm.target).attr('data-id');
            $(`#view-documento-${id}`)[0].submit();
        });
    </script>
@endsection
