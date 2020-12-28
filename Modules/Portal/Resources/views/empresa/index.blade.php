@extends('layouts.app')



@section('page_title', __('page_titles.portal.enterprise.index'))


@section('breadcrumbs')

    <li class="breadcrumb-item"><a href="{{ route('portal.home') }}"> @lang('page_titles.general.home') </a></li>
    <li class="breadcrumb-item active"> @lang('page_titles.portal.enterprise.index') </li>    

@endsection



@section('content')

    <div class="col-12">
        <div class="card">
            <div class="card-body">
                @if(Session::has('message'))
                    @component('components.alert')
                    @endcomponent

                    {{ Session::forget('message') }}
                @endif
            
               
            
                <div class="table-responsive m-t-40">
                    <table id="dataTable-empresas" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>CNPJ</th>
                                <th>Telefone</th>
                                <th>Controle</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($empresas as $empresa)
                                <tr>
                                    <td>{{ $empresa->nome }}</td>
                                    <td>{{ $empresa->cnpj }}</td>
                                    <td>{{ $empresa->telefone }}</td>
                                    <td>
                                        <!--<a href="#" class="btn waves-effect waves-light btn-danger sa-warning" data-id="{{ $empresa->id }}"> <i class="mdi mdi-delete"></i> @lang('buttons.general.delete') </a>
                                        <a href="{{ route('core.empresa.editar', ['id' => $empresa->id]) }}" class="btn waves-effect waves-light btn-info"> <i class="mdi mdi-lead-pencil"></i> @lang('buttons.general.edit') </a>-->
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-block btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> @lang('buttons.general.actions') </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="{{ route('portal.empresa.usuariosVinculados', ['id' => $empresa->id]) }}"> <i class="mdi mdi-account-settings-variant"></i> @lang('buttons.portal.enterprise.users') </a>
                                                <a class="dropdown-item" href="{{ route('portal.empresa.gruposVinculados', ['id' => $empresa->id]) }}"> <i class="mdi mdi-account-switch"></i> @lang('buttons.portal.enterprise.groups') </a>
                                                <a class="dropdown-item" href="{{ route('portal.empresa.processosVinculados', ['id' => $empresa->id]) }}"> <i class="mdi mdi-image-filter-none"></i> @lang('buttons.portal.enterprise.processes') </a>                                                    
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
            $('#dataTable-empresas').DataTable({
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
        
        
    </script>
@endsection