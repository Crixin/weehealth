@extends('core::layouts.app')

@extends('core::layouts.menuPortal')
@yield('menu')

@section('page_title', __('page_titles.ged.index'))

@section('breadcrumbs')

    <li class="breadcrumb-item"><a href="{{ route('home') }}"> @lang('page_titles.general.home') </a></li>
    <li class="breadcrumb-item"> @lang('page_titles.ged.index') </li>    
    <li class="breadcrumb-item active"> @lang('page_titles.ged.search') </li>    

    @endsection

@section('content')

    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive m-t-40 mt-4">
                    <table id="dataTable-registros" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                @foreach ( $cabecalho as $descricao )
                                    <th>{{ $descricao }}</th>
                                @endforeach
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($registros))
                                @foreach ($registros as $registro)
                                    <tr>
                                        @foreach($registro->listaIndice as $indice)
                                            <td>{{ $indice->valor ?? '' }}</td>
                                        @endforeach
                                        <td style="white-space: nowrap">
                                            <a href="{{ route('ged.editar', ['idRegistro' => $registro->id, 'empresaProcesso' => $empresaProcessoId]) }}" class="btn btn-info"> <i class="mdi mdi-pencil"></i> @lang('buttons.general.edit') </a>
                                            <button type='button' class="btn waves-effect waves-light btn-danger delete-register" data-id-registro="{{ $registro->id }}"> <i class="mdi mdi-delete"></i> @lang('buttons.general.delete') </button>

                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                @foreach ($documentos as $documento)
                                    <tr>
                                        @foreach($documento->listaIndice as $indice)
                                            <td>{{ $indice->valor ?? '' }}</td>
                                        @endforeach
                                        <td style="white-space: nowrap">
                                            <a href="{{ route('ged.access-document', [$empresaProcesso, $documento->id]) }}" class="btn btn-info"> <i class="mdi mdi-eye"></i> @lang('buttons.general.access') </a>
                                            <button type='button' class="btn waves-effect waves-light btn-danger delete-document" data-id-documento="{{ $documento->id }}"> <i class="mdi mdi-delete"></i> @lang('buttons.general.delete') </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                    <div class="col-md-12 m-t-40">
                        <div class="col-md-2 pull-right">
                            @if(isset($registros))
                                <a href=" {{ route('ged.search-view') }} " class="btn waves-effect waves-light btn-lg btn-block btn-secondary pull-right mt-4" >@lang('buttons.general.back') </a>
                            @else
                                <a href=" {{ route('ged.editar', ['idRegistro' => $idRegistro, 'empresaProcesso' => $empresaProcesso]) }} " class="btn waves-effect waves-light btn-lg btn-block btn-secondary pull-right mt-4" >@lang('buttons.general.back') </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @if (isset($documentos))
                <div class="card-body">
                    <h4>Inserir Documentos</h4>
                    <form method="POST" action="{{ route('ged.create-document') }}" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <input type="hidden" name="idRegistro" value="{{ $idRegistro }}" />
                        <input type="hidden" name="idArea" value="{{ $idArea }}" />
                        <input type="file" name="arquivo_upload[]" id="input-file-now" class="dropify" data-max-file-size="20M" multiple />
                        <button class="btn waves-effect waves-light btn-lg btn-block btn-success pull-right mt-3" >@lang('buttons.general.send') </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('footer')
    <link  href="{{ asset('plugins/viewerjs/viewer.min.css') }}" rel="stylesheet">
    
    <script src="{{ asset('plugins/viewerjs/viewer.min.js') }}"></script>
    <script src="{{ asset('plugins/jquery-viewer/jquery-viewer.min.js') }}"></script>
    <script src="{{ asset('plugins/blockUI/jquery.blockUI.js') }}"></script>

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
    <link rel="stylesheet" href="{{ asset('plugins/dropify/dist/css/dropify.min.css') }}">
    <script src="{{ asset('plugins/dropify/dist/js/dropify.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.dropify').dropify();

            let table = $('#dataTable-registros').DataTable({
                "stateSave": true,
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


            $('.delete-document').click(function(){
                let idDocumento = $(this).data('id-documento');
                let deleteIt = swal2_warning("Essa ação é irreversível!");
                let obj = {'documento_id': idDocumento};

                deleteIt.then(resolvedValue => {
                    ajaxMethod('POST', "{{ URL::route('deletar.documento') }}", obj).then(response => {
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

            $('.delete-register').click(function(){
                let idRegistro = $(this).data('id-registro');
                let deleteIt = swal2_warning("Essa ação é irreversível!");
                let obj = {'registro_id': idRegistro};

                deleteIt.then(resolvedValue => {
                    ajaxMethod('POST', "{{ URL::route('deletar.registro') }}", obj).then(response => {
                        if(response.response != 'erro') {
                            swal2_success("Excluído!", "Registro excluído com sucesso.");
                        } else {
                            swal2_alert_error_support("Tivemos um problema ao excluir o registro.");
                        }
                    }, error => {
                        console.log(error);
                    });
                }, error => {
                    swal.close();
                });
            });
        });
        
        function loading() {
            $.blockUI({ 
                message: "Carregando...",
                css: { 
                    border: 'none', 
                    padding: '15px', 
                    backgroundColor: '#000', 
                    '-webkit-border-radius': '10px', 
                    '-moz-border-radius': '10px', 
                    opacity: .5, 
                    color: '#fff' 
                }
            }); 
        }

        function done() {
            $.unblockUI()
        }
        

    </script>

@endsection