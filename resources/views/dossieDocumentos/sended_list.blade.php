@extends('app')

@section('page_title', __('page_titles.enterprise.index'))

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('home') }}"> @lang('page_titles.general.home') </a></li>
    <li class="breadcrumb-item active"> @lang('page_titles.enterprise.index') </li>    

    <link href="{{ asset('plugins/tag-input/bootstrap-tagsinput.css') }}" rel="stylesheet">

@endsection

@section('content')

    <div class="col-12">
        <div class="card">
            <div class="card-body">
                @if(Session::has('message'))
                    @component('componentes.alert') @endcomponent
                    {{ Session::forget('message') }}
                @endif
            
                <div class="col-md-12">
                    <a href="{{ route('dossieDocumentos.novo') }}" class="btn waves-effect waves-light btn-lg btn-success pull-right">@lang('buttons.dossie.create') </a>
                </div>
            
                <div class="table-responsive m-t-40">
                    <table id="dataTable-empresas" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Processos</th>
                                <th>Título</th>
                                <th>Validade</th>
                                <th>Status</th>
                                <th>Visualizações</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dossies as $dossie)
                                <tr style="white-space:nowrap">
                                    <td>{{ $dossie->id }}</td>
                                    <td>
                                        @foreach ($dossie->dossieEmpresaProcesso as $dossieEmpresaProcesso)
                                            {{ $dossieEmpresaProcesso->empresaProcesso->processo->nome ?? ""}} <br>
                                        @endforeach
                                    </td>
                                    <td>{{ $dossie->titulo }}</td>
                                    <td>{{ date('d/m/Y H:i:s', strtotime($dossie->validade)) }}</td>
                                    <td>{{ $dossie->status }}</td>
                                    <td>
                                        @foreach (unserialize($dossie->destinatarios) as $destinatario)
                                            <label>{{$destinatario['email']}} 
                                                @if ($destinatario['downloaded'])
                                                    <i class="fa fa-check" style="color: green"></i>
                                                @else
                                                    <i class="fa fa-close" style="color: red"></i>
                                                @endif
                                            </label><br>
                                        @endforeach    
                                    </td>
                                    <td>
                                        <a href="#" data-id="{{ $dossie->id }}" class="btn waves-effect waves-light btn-danger sa-warning"> <i class="mdi mdi-delete"></i> @lang('buttons.general.delete') </a>
                                        <a href="#" data-id="{{ $dossie->id }}" class="btn waves-effect waves-light btn-info resend"> <i class="mdi mdi-send"></i> @lang('buttons.general.resend') </a>
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
    <script src="{{ asset('plugins/tag-input/bootstrap-tagsinput.js') }}"></script>

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
        
        $('.sa-warning').click(function(){
            let deleteIt = swal2_warning("Essa ação é irreversível!");
            let obj = {'dossie': $(this).data('id')};

            deleteIt.then(resolvedValue => {
                ajaxMethod('POST', "{{ URL::route('dossie.deletar') }}", obj).then(response => {
                    if(response.response != 'erro') {
                        swal2_success("Excluído!", "Dossiê excluído com sucesso.");
                    } else {
                        swal2_alert_error_support("Tivemos um problema ao excluir o dossiê.");
                    }
                }, error => {
                    console.log(error);
                });
            }, error => {
                swal.close();
            });
        });

        $('.resend').click(function(){
            let question = swal2_warning("Deseja reenviar o email SOMENTE para quem ainda não visualizou?", "Sim, enviar!", "#5dc2f1");
            let obj = {'dossie': $(this).data('id')};

            question.then(resolvedValue => {
                ajaxMethod('POST', "{{ URL::route('ajax.resendDossie') }}", obj).then(response => {
                    if(response.response != 'erro') {
                        swal2_success("Enviado!", "Emails enviado com sucesso.");
                    } else {
                        swal2_alert_error_support("Tivemos um problema ao enviar os emails.");
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