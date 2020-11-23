@extends('layouts.app')

@extends('layouts.menuCore')
@yield('menu')


@section('page_title', __('page_titles.core.user.index'))


@section('breadcrumbs')

    <li class="breadcrumb-item"><a href="{{ route('core.home') }}"> @lang('page_titles.general.home') </a></li>
    <li class="breadcrumb-item active"> @lang('page_titles.core.user.index') </li>    

@endsection



@section('content')

    <div class="col-12">
        <div class="card">
            <div class="card-body">

                @if(Session::has('message'))
                    @component('componentes.alert')
                    @endcomponent
                    
                    {{ Session::forget('message') }}
                @endif

            
                <div class="table-responsive m-t-40">
                    <table id="dataTable-administradores" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Nome de Usuário</th>
                                <th>E-mail</th>
                                <th> 
                                    <a  href="javascript:void(0)" data-toggle="tooltip" data-placement="top" title="{{ __('messages.administratorPermission') }}"> 
                                        <i class="mdi mdi-help-circle-outline text-info"></i> Administrador?
                                    </a>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->username }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td> 
                                        <input type="checkbox" id="administrador-{{ $user->id }}" class="filled-in chk-col-cyan" {{ ($user->administrador) ? 'checked' : '' }} /> 
                                        <label for="administrador-{{ $user->id }}">@lang('buttons.general.active')</label>
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
            $('#dataTable-administradores').DataTable({
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



    {{-- Click nos checkboxes de permissionamento --}}
    <script>
        $(".filled-in").on('click', evt => {
            let idElemento = evt.currentTarget.id;
            let idUsuario = idElemento.split('-')[1];
            let valor = $("#" + idElemento).is(":checked");
            let mensagem = (valor == true) ? 'Este usuário agora é um administrador!' : 'Permissão de administrador removida com sucesso!';

            let obj = {'idUsuario': idUsuario, 'valor': valor};
            ajaxMethod('POST', "{{ URL::route('core.atualizar.permissaoAdministrador') }}", obj).then(response => {
                if(response.response === 'sucesso') {
                    swal2_success('Atualizado!', mensagem);
                } else {
                    swal2_alert_error_support("Tivemos um problema ao definir o usuário como administrador.");
                }
            }, error => {
                console.log(error);
            });
        });
    </script>
@endsection