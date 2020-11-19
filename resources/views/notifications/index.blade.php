@extends('layouts.app')

@extends('layouts.menuCore')
@yield('menu')

@section('page_title', __('page_titles.notifications.index'))


@section('breadcrumbs')

    <li class="breadcrumb-item"><a href="{{ route('home') }}"> @lang('page_titles.general.home') </a></li>
    <li class="breadcrumb-item active"> @lang('page_titles.notifications.index') </li>    

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
            
            <div class="col-md-12">
                <a href="{{ route('notificacao.marcar-todas-como-lidas') }}" class="btn waves-effect waves-light btn-lg btn-success pull-right">@lang('buttons.notifications.mark_all_as_read') </a>
            </div>

            <div class="table-responsive m-t-40">
                <table id="dataTable-notificacoes" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Título</th>
                            <th>Descrição</th>
                            <th>Última Modificação</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (Auth::user()->unreadNotifications as $n)
                        <tr id="tr-{{ $n->id }}">
                                <td>{{ $n->data['title'] }}</td>
                                <td>{{ $n->data['content'] }}</td>
                                <td>{{ date('d/m/Y H:i', strtotime($n->updated_at)) }}h</td>
                                <td>
                                    <button type="button" class="btn waves-effect waves-light btn-outline-secondary markAsRead" data-id="{{ $n->id }}"> @lang('buttons.general.mark_as_read') </button>
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
            $('#dataTable-notificacoes').DataTable({
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

    {{-- Marca a notificação como lida e remove a linha da tabela --}}
    <script>
        $(".markAsRead").on('click', function(evt) {
            let idNotificacao = $(this).data('id');
            let idTrElm = $(this).closest('tr').attr('id');
            let obj = {'notificacao_id': idNotificacao};

            ajaxMethod('POST', "{{ URL::route('notificacao.marcar-todas-como-lidas') }}", obj).then(response => {
                if(response.response != 'erro') {
                    showToast('Atualizada!', 'Notificação marcada como lida com sucesso!', 'success');
                    $("#" + idTrElm).remove();
                } else {
                    swal2_alert_error_support("Tivemos um problema ao atualizar a notificação.");
                }
            }, error => {
                console.log(error);
            });
        });
    </script>

@endsection