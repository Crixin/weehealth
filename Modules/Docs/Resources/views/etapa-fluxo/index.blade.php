@extends('layouts.app')




@section('page_title', __('page_titles.docs.etapa-fluxo.index'))


@section('breadcrumbs')

    <li class="breadcrumb-item"><a href="{{ route('docs.home') }}"> @lang('page_titles.general.home') </a></li>
    <li class="breadcrumb-item active"> @lang('page_titles.docs.etapa-fluxo.index') </li>    

@endsection



@section('content')

    <div class="col-12">
        <div class="card">
            <div class="card-body">

                    @if(Session::has('message'))
                        @component('components.alert')@endcomponent

                        {{ Session::forget('message') }}
                    @endif
                    
                    <div class="col-md-12">
                        <a href="{{ route('docs.fluxo') }}" class="btn btn-inverse"> @lang('buttons.general.back')</a>
                        <a href="{{ route('docs.fluxo.etapa-fluxo.novo', ['fluxo_id' => $fluxo->id]) }}" class="btn waves-effect waves-light  btn-success pull-right"><i class="fa fa-pencil"></i>&nbsp;@lang('buttons.docs.etapa-fluxo.create') </a>
                    </div>
                
                    <div class="table-responsive m-t-40">
                        <table id="dataTable-etapa" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Ordem</th>
                                    <th>Nome</th>
                                    <th>Descricao</th>
                                    <th>Controle</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($etapas as $etapa)
                                    <tr>
                                        <td>{{ $etapa->id }}</td>
                                        <td>{{ $etapa->ordem }}</td>
                                        <td>{{ $etapa->nome }}</td>
                                        <td>{{ $etapa->descricao }}</td>
                                        <td>
                                            <a href="#" class="btn waves-effect waves-light btn-danger sa-warning" data-id="{{ $etapa->id }}"> <i class="mdi mdi-delete"></i> @lang('buttons.general.delete') </a>
                                            <a href="{{ route('docs.fluxo.etapa-fluxo.editar', ['id' => $etapa->id, 'fluxo_id' => $fluxo->id]) }}" class="btn waves-effect waves-light btn-info"> <i class="mdi mdi-lead-pencil"></i> @lang('buttons.general.edit') </a>
                                            
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
            $('#dataTable-etapa').DataTable({
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
        
        // Exclusão do fluxo
        $('.sa-warning').click(function(){
            let id = $(this).data('id');
            let deleteIt = swal2_warning("Essa ação é irreversível!");
            let obj = {'id': id};

            deleteIt.then(resolvedValue => {
                ajaxMethod('POST', "{{ URL::route('docs.fluxo.etapa-fluxo.deletar', ['fluxo_id' => $fluxo->id]) }}", obj).then(response => {
                    if(response.response != 'erro') {
                        swal2_success("Excluído!", "Etapa do Fluxo excluída com sucesso.");
                    } else {
                        swal2_alert_error_support("Tivemos um problema ao excluir a etapa.");
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