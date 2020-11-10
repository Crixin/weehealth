@extends('core::layouts.app')

@extends('core::layouts.menuPortal')
@yield('menu')


@section('page_title', __('page_titles.configs.index_parameters'))


@section('breadcrumbs')

    <li class="breadcrumb-item"><a href="{{ route('core.home') }}"> @lang('page_titles.general.home') </a></li>
    <li class="breadcrumb-item active"> @lang('page_titles.configs.index_parameters') </li>    

@endsection



@section('content')

<div class="col-12">
    <div class="card">
        <div class="card-body">

                <div class="alert alert-info">
                    <ul>
                        <li>Você pode inserir os valores que desejar na coluna <span class="font-weight-bold" style="color: cornflowerblue">Valor Customizado</span>. Para isso, basta clicar sobre a tabela!</li>
                        <li>Além disso, você pode ativar ou inativar um parâmetro na coluna <span class="font-weight-bold" style="color: cornflowerblue">Ativo</span>!</li>
                    </ul>
                </div>
            
                <table class="table table-striped table-bordered" id="editable-datatable">
                    <thead>
                        <tr>
                            <th>Identificador Parâmetro</th>
                            <th>Descrição</th>
                            <th>Valor Padrão</th>
                            <th style="color: cornflowerblue">Valor Customizado</th>
                            <th style="color: cornflowerblue">Ativo</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($params as $p)
                            <tr id="tr_param-{{ $p->id }}" data-id="{{ $p->id }}" class="{{ ($p->ativo) ? 'text-black' : 'text-muted' }}">
                                <td id="identificador_parametro-{{ $p->id }}" class="edit-disabled">
                                    {{ $p->identificador_parametro }} 
                                </td>
                                <td id="descricao-{{ $p->id }}" class="edit-disabled">
                                    {{ $p->descricao }} 
                                </td>
                                <td id="valor_padrao-{{ $p->id }}" class="edit-disabled">
                                    {{ $p->valor_padrao }}  
                                </td>
                                <td id="valor_usuario-{{ $p->id }}"> {{ $p->valor_usuario }} </td>
                                <td id="ativo-{{ $p->id }}" class="edit-disabled"> 
                                    <button type="button" class="btn btn-block waves-effect waves-light btn-{{ ($p->ativo) ? 'danger' : 'success' }} changeParamValue" data-id="{{ $p->id }}" data-value="{{ !$p->ativo }}"> 
                                        {{ ($p->ativo) ? 'Inativar' : 'Ativar' }} 
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        
                    </tbody>
                </table>

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
            $('#editable-datatable').DataTable({
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

    <!-- Editable -->
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap.js') }}"></script>
    <script src="{{ asset('plugins/tiny-editable/mindmup-editabletable.js') }}"></script>
    <script src="{{ asset('plugins/tiny-editable/numeric-input-example.js') }}"></script>

    <script>
        $('#editable-datatable').editableTableWidget({editor: $('<textarea>'), disableClass: "edit-disabled"});
        
        $(document).ready(function() {
            $('#editable-datatable').DataTable();

            // Alteração na coluna 'valor_usuario'
            $('table td').on('change', function(evt, newValue) {
                let idParametro = $(this).closest('tr').data('id');
                let coluna = $(this).attr('id').split('-')[0];
                
                let obj = {'parametro_id': idParametro, 'coluna': coluna, 'valor': newValue};
                ajaxMethod('POST', "{{ URL::route('atualizar.parametro') }}", obj).then(response => {
                    if(response.response != 'erro') {
                        showToast('Atualizado!', 'Valor do parâmetro atualizado com sucesso!', 'success');
                    } else {
                        swal2_alert_error_support("Tivemos um problema ao atualizar o valor do parâmetro.");
                    }
                }, error => {
                    console.log(error);
                });
            });

            // 'Click' no botão para ativar ou desativar o parâmetro
            $('.changeParamValue').on('click', function(evt) {
                let idParam = $(this).data('id');
                let newValue = ($(this).data('value')) ? true : false;
                
                let obj = {'parametro_id': idParam, 'coluna': 'ativo', 'valor': newValue};
                ajaxMethod('POST', "{{ URL::route('atualizar.parametroAtivo') }}", obj).then(response => {
                    if(response.response != 'erro') {
                        swal2_success("Sucesso!", "Valor do parâmetro atualizado com sucesso!");
                    } else {
                        swal2_alert_error_support("Tivemos um problema ao atualizar o valor do parâmetro.");
                    }
                }, error => {
                    console.log(error);
                });
            });

        });
    </script>
@endsection