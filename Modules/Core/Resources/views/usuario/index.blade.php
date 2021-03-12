@extends('layouts.app')




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
                    @component('components.alert')
                    @endcomponent
                    
                    {{ Session::forget('message') }}
                @endif


                <div class="col-md-12">
                    <a href="{{ url('core/usuario/register') }}" class="btn  waves-effect waves-light btn-success pull-right"><i class="fa fa-pencil"></i>&nbsp;@lang('buttons.core.user.create') </a>
                </div>
            
                <div class="table-responsive m-t-40">
                    <table id="dataTable-usuarios" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nome</th>
                                <th>Nome de Usuário</th>
                                <th>E-mail</th>
                                <th>Perfil</th>
                                <th> 
                                    <a  href="javascript:void(0)" data-toggle="tooltip" data-placement="top" title="{{ __('messages.userPermission') }}"> 
                                        <i class="mdi mdi-help-circle-outline text-info"></i> Permissão
                                    </a>
                                </th>
                                <th>Controle</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($usuarios as $usuario)
                                <tr>
                                    <td>{{ $usuario->id }}</td>
                                    <td>{{ $usuario->name }}</td>
                                    <td>{{ $usuario->username }}</td>
                                    <td>{{ $usuario->email }}</td>
                                    <td>{{ $usuario->corePerfil->nome ?? "" }}</td>
                                    <td> 
                                        <input type="checkbox" id="permissao_nivel_usuario-{{ $usuario->id }}" class="filled-in chk-col-cyan" {{ ($usuario->permissao_nivel_usuario) ? 'checked' : '' }} /> 
                                        <label for="permissao_nivel_usuario-{{ $usuario->id }}">Ativa</label>
                                    </td>
                                    <td>
                                        @if ($usuario->inativo == 0)
                                            <a href="#" style="width: 90px" class="btn waves-effect waves-light btn-warning sa-warning" data-id="{{ $usuario->id }}"> <i class="mdi mdi-account-off"></i> @lang('buttons.general.disable') </a>
                                        @else
                                            <a href="#" style="width: 90px" class="btn waves-effect waves-light btn-success sa-success" data-id="{{ $usuario->id }}"> <i class="mdi mdi-account-settings"></i> @lang('buttons.general.enable') </a>
                                            
                                        @endif
                                        <a href="{{ route('core.usuario.editar', ['id' => $usuario->id ]) }}" class="btn waves-effect waves-light btn-info"> <i class="mdi mdi-lead-pencil"></i> @lang('buttons.general.edit') </a>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-block btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> @lang('buttons.general.actions') </button>
                                            <div class="dropdown-menu">
                                                <a class="dropdown-item" href="{{ route('core.usuario.substituir', ['id' => $usuario->id]) }}"> <i class="mdi mdi-account-convert"></i> @lang('buttons.core.user.change-users') </a>  
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


     <!-- Modal de confimação: deseja, realmente, trocar a permissão do usuário? -->
     <div class="modal" id="modalChangeUserPermission" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">Confirmação - Mudança de Permissão</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <h4>Leia com atenção o texto abaixo!</h4>
                    <p id="descricao_mudanca_permissao"></p>
                    <p>Dito isso, <span class="font-weight-bold">deseja realmente aplicar essa alteração?</span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" style="border-color: black" class="btn btn-back waves-effect" data-dismiss="modal"> @lang('buttons.general.cancel') </button>
                    <button type="button" id="confirm_change_permission" class="btn btn-danger waves-effect"> @lang('buttons.general.confirm') </button>
                </div>
            </div>
        </div>
    </div>
    <!-- /.Fecha modal de confimação: deseja, realmente, trocar a permissão do usuário? -->
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
            $('#dataTable-usuarios').DataTable({
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

            $(".filled-in").on('click', evt => {
            let idElemento = evt.currentTarget.id;
            let idUsuario = idElemento.split('-')[1];
            let valor = $("#" + idElemento).is(":checked");

            window.sessionStorage.setItem('ID_USUARIO_MUDANCA_PERMISSAO', idUsuario);
            window.sessionStorage.setItem('VALOR_MUDANCA_PERMISSAO', valor);

            let texto = (valor) ? 'Ao ativar a permissão à nível de usuário, você estará indicando ao sistema que apenas os vínculos DIRETOS entre USUÁRIO e EMPRESA devem ser levados em conta. Além disso, todas as vinculações do usuário com algum grupo serão removidas e, caso você deseje alterar essas permissões posteriormente, deverá refazer os vínculos que foram excluídos.' : 'Ao desativar a permissão à nível de usuário, você está indicando ao sistema para considerar apenas os vínculos dos GRUPOS que esse usuário pertence, com as EMPRESAS. Ou seja, nenhum vínculo feito diretamente com o usuário surtirá efeito!';
            $("#descricao_mudanca_permissao").text(texto);
            $("#modalChangeUserPermission").modal({backdrop: 'static', keyboard: false});
            });

            // Quando o modal de confirmação é fechada através do botão cancelar, limpa a sessão e recarrega a página
            $('.btn-back, .close').on('click', function (e) {
                $("#modalChangeUserPermission").hide();
                location.reload();
            })

            // Quando o usuário confirmar a mudança de nível de permissão em um dos usuários
            $("#confirm_change_permission").on('click', function() {
                let idUsuario = window.sessionStorage.getItem('ID_USUARIO_MUDANCA_PERMISSAO');
                let valor     = (window.sessionStorage.getItem('VALOR_MUDANCA_PERMISSAO') == "false") ? false : true;
                window.sessionStorage.clear();

                let obj = {'idUsuario': idUsuario, 'valor': valor};
                ajaxMethod('POST', "{{ URL::route('core.atualizar.permissaoUsuario') }}", obj).then(response => {
                    console.log(response);
                    if(response.response != 'erro') {
                        swal2_success('Atualizado!', 'O nível de permissão do usuário foi atualizado com sucesso!');
                    } else {
                        swal2_alert_error_support("Tivemos um problema ao atualizar o nível de permissão do usuário.");
                    }
                }, error => {
                    console.log(error);
                });
            });

            

        });

        // Inativacao de usuário
        $('.sa-warning').click(function(){
            let idUser   = $(this).data('id');
            let msg = swal2_warning("O usuário será inativado!", 'Sim, inativar!');
            ativarInativar(idUser, msg, 'inativar');
        });

        // ativacao de usuário
        $('.sa-success').click(function(){
            let idUser   = $(this).data('id');
            let msg = swal2_warning("O usuário será ativado!", 'Sim, ativar!');
            ativarInativar(idUser, msg, 'ativar');
        });

        function ativarInativar(idUser, msg, operacao)
        {
            let obj = {'id': idUser, 'operacao': operacao, _token: "{{ csrf_token() }}"};

            msg.then(resolvedValue => {
                ajaxMethod('POST', "{{ URL::route('core.usuario.inativar') }}", obj).then(response => {
                    if(response.response != 'erro') {
                        swal2_success("Sucesso!", response.message);
                    } else {
                        swal2_alert_error_support(response.message);
                    }
                }, error => {
                    swal2_alert_error_support(response.message);
                });
            }, error => {
                swal.close();
            });
        }
    </script>


    


@endsection