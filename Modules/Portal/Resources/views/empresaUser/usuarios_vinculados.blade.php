@extends('core::layouts.app')

@extends('core::layouts.menuPortal')
@yield('menu')


@section('page_title', __('page_titles.enterprise.linked_users'))


@section('breadcrumbs')

    <li class="breadcrumb-item"><a href="{{ route('core.home') }}"> @lang('page_titles.general.home') </a></li>
    <li class="breadcrumb-item"><a href="{{ route('empresa') }}"> @lang('page_titles.enterprise.index') </a></li>
    <li class="breadcrumb-item active"> @lang('page_titles.enterprise.linked_users') </li>    

@endsection



@section('content')

    <div class="col-12">
        <div class="card">
            <div class="card-body">


                @if(Session::has('message'))
                    @component('core::componentes.alert')
                    @endcomponent

                    {{ Session::forget('message') }}
                @endif

                <form method="POST" action="{{ route('empresa.vincularUsuarios') }}">
                    {{ csrf_field() }}
                    <input type="hidden" name="idEmpresa" value="{{ $empresa->id }}">
                    
                    <div class="form-body">
                        
                        {{-- Parte 1: cadastro --}}
                        <h3 class="box-title"> @lang('page_titles.enterprise.users_available') </h3>
                        <hr class="m-t-0 m-b-10">

                        @if ($usuariosRestantes->count() > 0)
                            <div class="row p-t-20">
                                <div class="col-md-12 m-b-30">
                                    <select multiple id="usuarios_empresa" name="usuarios_empresa[]">
                                        @foreach ($usuariosRestantes as $usuario)
                                            @if ($empresa->coreUsers->contains('id', $usuario->id))
                                                <option value="{{ $usuario->id }}" selected>{{ $usuario->name }}</option>
                                            @else
                                                <option value="{{ $usuario->id }}">{{ $usuario->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <div class="button-box m-t-20"> <a id="select-all" class="btn btn-danger" href="#"> @lang('buttons.general.select_all') </a> <a id="deselect-all" class="btn btn-info" href="#"> @lang('buttons.general.deselect_all') </a> </div>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> @lang('buttons.general.save')</button>
                                <a href="{{ route('empresa') }}" class="btn btn-inverse"> @lang('buttons.general.back')</a>
                            </div>
                        @else
                            <div class="row p-t-20 m-b-40">
                                <div class="col-md-12 text-center">
                                    <div class="alert alert-warning"> <i class="mdi mdi-alert-circle"></i> Todos os usuários do sistema já estão vinculados à empresa <span class="font-weight-bold">{{ $empresa->nome }}</span>.
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions">
                                <a href="{{ route('empresa') }}" class="btn btn-inverse"> @lang('buttons.general.back')</a>
                            </div>
                        @endif


                        {{-- Parte 2: listagem --}}
                        <h3 class="box-title m-t-40">  @lang('page_titles.enterprise.linked_users_to') <span style="font-weight: bold;">{{ $empresa->nome }}</span> <small>- Defina as permissões de cada um abaixo</small> </h3>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                @if ($usuariosJaVinculados->count() > 0)    
                                    <div class="table-responsive">
                                        <table id="dataTable-empresa-usuarios" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>Nome</th>
                                                    <th>Email</th>
                                                    @if (Helper::isParamActive('PERMITIR_DOWNLOAD'))
                                                        <th> {{ Helper::getParamValue('PERMITIR_DOWNLOAD') }} </th>
                                                    @endif
                                                    @if (Helper::isParamActive('PERMITIR_VISUALIZAR'))
                                                        <th> {{ Helper::getParamValue('PERMITIR_VISUALIZAR') }} </th>
                                                    @endif
                                                    @if (Helper::isParamActive('PERMITIR_EDITAR'))
                                                        <th> {{ Helper::getParamValue('PERMITIR_EDITAR') }} </th>
                                                    @endif
                                                    @if (Helper::isParamActive('PERMITIR_IMPRIMIR'))
                                                        <th> {{ Helper::getParamValue('PERMITIR_IMPRIMIR') }} </th>
                                                    @endif
                                                    @if (Helper::isParamActive('PERMITIR_APROVAR'))
                                                        <th> {{ Helper::getParamValue('PERMITIR_APROVAR') }} </th>
                                                    @endif
                                                    @if (Helper::isParamActive('PERMITIR_EXCLUIR'))
                                                        <th> {{ Helper::getParamValue('PERMITIR_EXCLUIR') }} </th>
                                                    @endif
                                                    @if (Helper::isParamActive('PERMITIR_UPLOAD'))
                                                        <th> {{ Helper::getParamValue('PERMITIR_UPLOAD') }} </th>
                                                    @endif
                                                    @if (Helper::isParamActive('PERMITIR_RECEBER_EMAIL'))
                                                        <th> {{ Helper::getParamValue('PERMITIR_RECEBER_EMAIL') }} </th>
                                                    @endif
                                                    <th>Remover Vínculo</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($usuariosJaVinculados as $usuarioV)
                                                    <tr>
                                                        <td><b> {{ $usuarioV->coreUser->name }} </b></td>
                                                        <td> {{ $usuarioV->coreUser->email }} </td>
                                                        @if (Helper::isParamActive('PERMITIR_DOWNLOAD'))
                                                            <td> 
                                                                <input type="checkbox" id="permissao_download-{{ $usuarioV->id }}" class="filled-in chk-col-cyan" {{ ($usuarioV->permissao_download) ? 'checked' : '' }} /> 
                                                                <label for="permissao_download-{{ $usuarioV->id }}">Habilitado</label>
                                                            </td>
                                                        @endif
                                                        @if (Helper::isParamActive('PERMITIR_VISUALIZAR'))
                                                            <td>
                                                                <input type="checkbox" id="permissao_visualizar-{{ $usuarioV->id }}" class="filled-in chk-col-cyan" {{ ($usuarioV->permissao_visualizar) ? 'checked' : '' }} disabled/> 
                                                                <label for="permissao_visualizar-{{ $usuarioV->id }}" class="text-muted">Habilitado</label>
                                                            </td>
                                                        @endif
                                                        @if (Helper::isParamActive('PERMITIR_EDITAR'))
                                                        <td>
                                                            <input type="checkbox" id="permissao_editar-{{ $usuarioV->id }}" class="filled-in chk-col-cyan" {{ ($usuarioV->permissao_editar) ? 'checked' : '' }} /> 
                                                            <label for="permissao_editar-{{ $usuarioV->id }}">Habilitado</label>
                                                        </td>                                                        
                                                    @endif
                                                        @if (Helper::isParamActive('PERMITIR_IMPRIMIR'))
                                                            <td>
                                                                <input type="checkbox" id="permissao_impressao-{{ $usuarioV->id }}" class="filled-in chk-col-cyan" {{ ($usuarioV->permissao_impressao) ? 'checked' : '' }} /> 
                                                                <label for="permissao_impressao-{{ $usuarioV->id }}">Habilitado</label>
                                                            </td>                                                        
                                                        @endif
                                                        @if (Helper::isParamActive('PERMITIR_APROVAR'))
                                                            <td>
                                                                <input type="checkbox" id="permissao_aprovar_doc-{{ $usuarioV->id }}" class="filled-in chk-col-cyan" {{ ($usuarioV->permissao_aprovar_doc) ? 'checked' : '' }} /> 
                                                                <label for="permissao_aprovar_doc-{{ $usuarioV->id }}">Habilitado</label>
                                                            </td>
                                                        @endif
                                                        @if (Helper::isParamActive('PERMITIR_EXCLUIR'))
                                                            <td>
                                                                <input type="checkbox" id="permissao_excluir_doc-{{ $usuarioV->id }}" class="filled-in chk-col-cyan" {{ ($usuarioV->permissao_excluir_doc) ? 'checked' : '' }} /> 
                                                                <label for="permissao_excluir_doc-{{ $usuarioV->id }}">Habilitado</label>
                                                            </td>
                                                        @endif
                                                        @if (Helper::isParamActive('PERMITIR_UPLOAD'))
                                                            <td>
                                                                <input type="checkbox" id="permissao_upload_doc-{{ $usuarioV->id }}" class="filled-in chk-col-cyan" {{ ($usuarioV->permissao_upload_doc) ? 'checked' : '' }} /> 
                                                                <label for="permissao_upload_doc-{{ $usuarioV->id }}">Habilitado</label>
                                                            </td>
                                                        @endif
                                                        @if (Helper::isParamActive('PERMITIR_RECEBER_EMAIL'))
                                                            <td>
                                                                <input type="checkbox" id="permissao_receber_email-{{ $usuarioV->id }}" class="filled-in chk-col-cyan" {{ ($usuarioV->permissao_receber_email) ? 'checked' : '' }} /> 
                                                                <label for="permissao_receber_email-{{ $usuarioV->id }}">Habilitado</label>
                                                            </td>
                                                        @endif
                                                        <td> 
                                                            <a href="#" class="btn waves-effect waves-light btn-danger sa-warning" data-id="{{ $usuarioV->id }}"> <i class="mdi mdi-delete"></i> @lang('buttons.general.unlink') </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="alert alert-info"> <i class="mdi mdi-alert-circle"></i> A empresa <span class="font-weight-bold">{{ $empresa->nome }}</span> ainda não possui nenhum usuário vinculado.
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                    </div>
                                @endif
                            </div>
                        </div>

                    </div>

                </form>

            </div>
        </div>
    </div>
    
@endsection


@section('footer')
    {{-- MultiSelect --}}
    <link href="{{ asset('plugins/multiselect/css/multi-select.css') }}" rel="stylesheet" type="text/css" />
    <script src="{{ asset('plugins/multiselect/js/jquery.multi-select.js') }}" type="text/javascript" ></script>
    <script src="{{ asset('plugins/quicksearch/jquery.quicksearch.js') }}" type="text/javascript" ></script>

    <script>
        $('#usuarios_empresa').multiSelect({ 
            keepOrder: true,
            selectableHeader: "<input type='text' class='form-control search-input' autocomplete='off' placeholder='Pesquisar usuários do sistema'>",
            selectionHeader: "<input type='text' class='form-control search-input' autocomplete='off' placeholder='Pesquisar usuários já vinculados à empresa'>",
            afterInit: function(ms){
                var that = this,
                    $selectableSearch = that.$selectableUl.prev(),
                    $selectionSearch = that.$selectionUl.prev(),
                    selectableSearchString = '#'+that.$container.attr('id')+' .ms-elem-selectable:not(.ms-selected)',
                    selectionSearchString = '#'+that.$container.attr('id')+' .ms-elem-selection.ms-selected';

                that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
                .on('keydown', function(e){
                if (e.which === 40){
                    that.$selectableUl.focus();
                    return false;
                }
                });

                that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
                .on('keydown', function(e){
                if (e.which == 40){
                    that.$selectionUl.focus();
                    return false;
                }
                });
            },
            afterSelect: function(){
                this.qs1.cache();
                this.qs2.cache();
            },
            afterDeselect: function(values){
                this.qs1.cache();
                this.qs2.cache();
            }
        });

        $('#select-all').click(function() {
            $('#usuarios_empresa').multiSelect('select_all');
            return false;
        });
        $('#deselect-all').click(function() {
            $('#usuarios_empresa').multiSelect('deselect_all');
            return false;
        });

        // Removendo a classe que tornava o multiselect tamanho único (e permitindo que ocupe a tela / width inteira)
        $("#ms-usuarios_empresa").css("width", "auto");
    </script>


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
            $('#dataTable-empresa-usuarios').DataTable({
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
        
        // Exclusão de víncula entre empresa e usuário
        $('.sa-warning').click(function(){
            let idVinculoEmpresaUsuario = $(this).data('id');
            let deleteIt = swal2_warning("Essa ação é irreversível!");
            let obj = {'vinculo_id': idVinculoEmpresaUsuario};

            deleteIt.then(resolvedValue => {
                ajaxMethod('POST', "{{ URL::route('relacao.empresaUsuario.deletar') }}", obj).then(response => {
                    if(response.response != 'erro') {
                        swal2_success("Excluído!", "Vínculo entre empresa e usuário excluído com sucesso.");
                    } else {
                        swal2_alert_error_support("Tivemos um problema ao excluir o vínculo.");
                    }
                }, error => {
                    console.log(error);
                });
            }, error => {
                swal.close();
            });
        });
    </script>


    {{-- Click nos checkboxes de permissionamento --}}
    <script>
        $(".filled-in").on('click', evt => {
            let idElemento = evt.currentTarget.id;

            // Permissão de visualização deve estar habilitada sempre!
            if(idElemento.split('-')[0] == 'permissao_visualizar')
                return;

            let idVinculo = idElemento.split('-')[1];
            let colunaModificada = idElemento.split('-')[0];
            let valor = $("#" + idElemento).is(":checked");
            
            let obj = {'idVinculo': idVinculo, 'coluna': colunaModificada, 'valor': valor};
            ajaxMethod('POST', "{{ URL::route('atualizar.relacao.empresaUsuario') }}", obj).then(response => {
                if(response.response != 'erro') {
                    showToast('Atualizado!', 'Permissão do usuário atualizada com sucesso.', 'success');
                } else {
                    swal2_alert_error_support("Tivemos um problema ao atualizar a permissão do usuário.");
                }
            }, error => {
                console.log(error);
            });
        });
    </script>
@endsection