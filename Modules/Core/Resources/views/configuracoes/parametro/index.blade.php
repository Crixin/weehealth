@extends('layouts.app')




@section('page_title', __('page_titles.core.configs.index_parameters'))


@section('breadcrumbs')

    <li class="breadcrumb-item"><a href="{{ route('core.home') }}"> @lang('page_titles.general.home') </a></li>
    <li class="breadcrumb-item active"> @lang('page_titles.core.configs.index_parameters') </li>    

@endsection



@section('content')

<div class="col-12">
    <div class="card">
        <div class="card-body">
                @if(Session::has('message'))
                    @component('components.alert')@endcomponent

                    {{ Session::forget('message') }}
                @endif
                <div class="alert alert-info">
                    <ul>
                        
                        <li>Você pode ativar ou inativar um parâmetro na coluna <span class="font-weight-bold" style="color: cornflowerblue">Ativo</span>!</li>
                    </ul>
                </div>
            
                <table class="table table-striped table-bordered" id="editable-datatable">
                    <thead>
                        <tr>
                            <th>Identificador Parâmetro</th>
                            <th>Descrição</th>
                            <th>Valor Padrão</th>
                            
                            <th>Controle</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($params as $p)
                            <tr id="tr_param-{{ $p->id }}" data-id="{{ $p->id }}" class="{{ ($p->ativo) ? 'text-black' : 'text-muted' }}">
                                <td id="identificador_parametro-{{ $p->id }}" >
                                    {{ $p->identificador_parametro }} 
                                </td>
                                <td id="descricao-{{ $p->id }}" >
                                    {{ $p->descricao }} 
                                </td>
                                <td  id="valor_padrao-{{ $p->id }}">
                                    {{ trim($p->valor_padrao) }}  
                                </td>
            
                                <td> 
                                    
                                    <a href="#" class="btn btn-block waves-effect waves-light btn-{{ ($p->ativo) ? 'danger' : 'success' }} changeParamValue" data-id="{{ $p->id }}" data-value="{{ !$p->ativo }}" style="width: 85px">  {{ ($p->ativo) ? 'Inativar' : 'Ativar' }}  </a>
                                    <a href="{{ route('core.configuracao.parametros.editar', ['id' => $p->id]) }}" class="btn waves-effect waves-light btn-info"> <i class="mdi mdi-lead-pencil"></i> @lang('buttons.general.edit') </a>
                                        
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
        //$('#editable-datatable').editableTableWidget({editor: $('<textarea>'), disableClass: "edit-disabled"});
        
        $(document).ready(function() {
            $('#editable-datatable').DataTable();

           

            // 'Click' no botão para ativar ou desativar o parâmetro
            $('.changeParamValue').on('click', function(evt) {
                let idParam = $(this).data('id');
                let newValue = ($(this).data('value')) ? true : false;
                
                let obj = {'parametro_id': idParam, 'coluna': 'ativo', 'valor': newValue};
                ajaxMethod('POST', "{{ URL::route('core.atualizar.parametroAtivo') }}", obj).then(response => {
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


         // Alteração na coluna 'valor_usuario'
         /*
        $(document).on("change","table td", function(evt, newValue) {
            let idParametro = $(this).closest('tr').data('id');
            let coluna = $(this).attr('id').split('-')[0];
            
            let obj = {'parametro_id': idParametro, 'coluna': coluna, 'valor': newValue.toUpperCase()};
            ajaxMethod('POST', "{{ URL::route('core.atualizar.parametro') }}", obj).then(response => {
                if(response.response != 'erro') {
                    showToast('Atualizado!', 'Valor do parâmetro atualizado com sucesso!', 'success');
                } else {
                    swal2_alert_error_support("Tivemos um problema ao atualizar o valor do parâmetro.");
                }
            }, error => {
                console.log(error);
            });
        });
        */

    </script>
@endsection