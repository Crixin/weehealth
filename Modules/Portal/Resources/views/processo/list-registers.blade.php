@extends('layouts.app')




@section('page_title', __('page_titles.portal.process.index'))


@section('breadcrumbs')

    <li class="breadcrumb-item"><a href="{{ route('portal.home') }}"> @lang('page_titles.general.home') </a></li>
    <li class="breadcrumb-item"> @lang('page_titles.portal.process.index') </li>    
    <li class="breadcrumb-item"> @lang('page_titles.portal.process.search') </li>    
    <li class="breadcrumb-item active"> @lang('page_titles.portal.process.list_registers') </li>    

@endsection



@section('content')

        <div class="col-12">
                <div class="card">
                        <div class="card-body">


                                @if(is_array($registros) && count($registros) > 0)

                                        <div class="table-responsive m-t-40">
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
                                                                @foreach ($registros as $registro)
                                    <tr>
                                                                                @foreach($registro->listaIndice as $indice)
                                                                                        <td>{{ $indice->valor ?? '' }}</td>
                                        @endforeach
                                        <td style="white-space: nowrap">
                                            <a href="{{ route('portal.processo.listarDocumentos', ['_idRegistro' => $registro->id]) }}" class="btn btn-info"> <i class="mdi mdi-eye"></i> @lang('buttons.general.view') </a>
                                            <a href='#' class="btn btn-info buscaDocs" data-carregado="false" data-visible="false" data-registro="{{$registro->id}}"> <i class="mdi mdi-view-list"></i> @lang('buttons.general.preview')</a>
                                        </td>
                                    </tr>
                                @endforeach
                                                        </tbody>
                                                </table>
                    </div>
                    <div class="col-md-12 m-t-40">
                        <div class="col-md-2 pull-right">
                            <a href="{{ route('portal.processo.buscar', ['idEmpresa' => session('identificadores')['_idEmpresa'], 'idProcesso' => session('identificadores')['_idProcesso'] ] ) }}" class="btn btn-secondary btn-lg btn-block pull-right"> @lang('buttons.general.back') </a>
                        </div>
                    </div>
                                @else
                                
                                        <p class="text-center text-danger" style="font-size: x-large;"> @lang('action.messages.no_registers') </p>
                                        <div class="container">
                                                <hr>    
                                                <div class="row">
                                                        <div class="col"></div>
                                                        <div class="col-4">
                                                         <a href="{{ route('portal.processo.buscar', ['idEmpresa' => session('identificadores')['_idEmpresa'], 'idProcesso' => session('identificadores')['_idProcesso'] ] ) }}" class="btn btn-secondary btn-lg btn-block pull-right"> @lang('buttons.general.back') </a>
                                                        </div>
                                                        <div class="col"></div>
                                                </div>
                                        </div>
                                @endif

                        </div>
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

    <script>
        $(document).ready(function() {
            let table = $('#dataTable-registros').DataTable({
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

            $('#dataTable-registros tbody').on('click', 'td a.buscaDocs', function () {
                
                let carregado = $(this).data("carregado");
                let registro = $(this).data("registro");
                let visible = $(this).data("visible");

                if (!carregado){
                    $(this).data("carregado", true);
                }
                
                let fileTable = $("#" + registro);
                
                let tr = $(this).closest('tr');
                let row = table.row( tr );

                if (carregado && visible) {
                    $(this).data("visible", false);
                    fileTable.hide();
                } else {
                    if (!carregado) {

                        loading();

                        buscaDocs(registro).then(docs => {
                            row.child(formatDocsRegister(docs, registro)).show();
                            $(this).data("visible", true);
                        
                            let images = $('.images');

                            images.viewer({
                                modal: true,
                                viewed: function() {
                                    $image.viewer('zoomTo', 1);
                                }
                            });
                            done();
                        });
                    } else {
                        $(this).data("visible", true);
                        fileTable.show();
                    }
                }
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

        function formatDocsRegister(docs, registro) {

            let htmlRender = '<div class="row" id=' + registro + '>'
            docs.forEach(doc => {
                var decoded = doc.bytes;
                
                let extensao = doc.endereco.split(".").pop()

                switch (extensao) {
                    case "pdf":
                        htmlRender += '<div class="col-md-4 mb-4"><label>' + doc.endereco + '</label><br><iframe class="rounded" src="data:application/' + extensao + ';base64,' + doc.bytes + '" width="310px" height="400px"></iframe></div>'
                        break;
                        
                    default:
                        htmlRender += '<div class="col-md-4 mb-4"><label>' + doc.endereco + '</label><br><img class="images rounded" src="data:image/' + extensao + ';base64,' + doc.bytes + '" width="310px" height="400px"></img></div>'
                        break;
                }
            });

            if (htmlRender == '<div class="row" id=' + registro + '>') {
                htmlRender += '<div class="col-md-4"><label><b>Sem documentos</b></label></div>'
            }

            return htmlRender += '</div>'
        }

        function buscaDocs(registro) {
            return new Promise(function (resolve, reject){
                $.ajax({
                    url: '{{route("portal.ged.getRegistro")}}',
                    type: 'GET',
                    dataType: 'JSON',
                    data: {
                        "id": registro,
                        "params": {
                            "docs": "true",
                            "bytes": "true",
                        }
                    },
                    headers: {
                        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (resp) {
                        if (!resp.error) {
                            resp = resp.response.listaDocumento
                            resolve(resp);
                        }
                    }
                });
            })
        }

    </script>

@endsection