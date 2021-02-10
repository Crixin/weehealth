<div class="form-body">
    <div class="row p-t-20">
        <div class="col-md-6">
            <div class="form-group required{{ $errors->has('descricao') ? ' has-error' : '' }}">
                {!! Form::label('descricao', 'Descrição', ['class' => 'control-label']) !!}
                {!! Form::text('descricao', $descricao, ['class' => 'form-control', 'required' => 'required']) !!}
                <small class="text-danger">{{ $errors->first('descricao') }}</small>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group required{{ $errors->has('orgaoRegulador') ? ' has-error' : '' }}">
                {!! Form::label('orgaoRegulador', 'Orgão Regulador', ['class' => 'control-label']) !!}
            
                {!! Form::select('orgaoRegulador', $orgaos, !empty($normaEdit) ?  $normaEdit->orgao_regulador_id : null, ['id' => 'orgaoRegulador', 'class' => 'form-control selectpicker ', 'required' => true, 'placeholder' => __('components.selectepicker-default') ]) !!}
                <small class="text-danger">{{ $errors->first('orgaoRegulador') }}</small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group required{{ $errors->has('cicloAuditoria') ? ' has-error' : '' }}">
                {!! Form::label('cicloAuditoria', 'Ciclo de Auditoria', ['class' => 'control-label']) !!}
            
                {!! Form::select('cicloAuditoria', $ciclos, !empty($normaEdit) ?  $normaEdit->ciclo_auditoria_id : null, ['id' => 'cicloAuditoria', 'class' => 'form-control selectpicker ', 'required' => true, 'placeholder' => __('components.selectepicker-default') ]) !!}
                <small class="text-danger">{{ $errors->first('cicloAuditoria') }}</small>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group {{ $errors->has('dataAcreditacao') ? ' has-error' : '' }}">
            {!! Form::label('dataAcreditacao', 'Data Acreditação' ) !!}
            {!! Form::date('dataAcreditacao', !empty($normaEdit) ?  $normaEdit->data_acreditacao : null, ['class' => 'form-control']) !!}
            <small class="text-danger">{{ $errors->first('dataAcreditacao') }}</small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <div class="checkbox required{{ $errors->has('vigente') ? ' has-error' : '' }}">
                    {!! Form::label('status', 'Vigente', ['class' => 'control-label']) !!}
                    <td class="text-center text-nowrap">
                        <div class="switch">
                            <label for="vigente">Não
                            {!! Form::checkbox('vigente', '1', !empty($normaEdit) ?  $normaEdit->ativo : true, ['id' => 'vigente', 'class'=> 'switch-elaborador']) !!}
                            <span class="lever switch-col-light-blue"></span>Sim
                            </label>
                        </div>
                    </td>    
                </div>
                <small class="text-danger">{{ $errors->first('vigente') }}</small>
            </div>
        </div>
    </div>
</div>
<legend>@lang('page_titles.docs.item-norma.index')</legend>
<hr>
<div class="table-responsive m-t-40">
    <button type="button" id="btnItemNorma" class="btn btn-info">@lang('buttons.docs.item-norma.create')</button>
    <table id="itens" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Id</th>
                <th>Descricao</th>
                <th>Checklist</th>
                <th>Controle</th>           
            </tr>
        </thead>
        <tbody id="itens">
            @foreach ($itens ?? [] as $item)
                @php
                    $conteudoBotao = 
                    [
                        "id"        => $item['id'],
                        "numero"    => $item['numero'],
                        "descricao" => $item['descricao'],
                        "checklist" => $item['checklist'],
                    ];
                @endphp
                <tr>
                    <td data-id="{{$item['id']}}">{{$item['numero']}}</td>
                    <td>{{$item['descricao']}}</td>
                    <td>{{$item['checklist']}}</td>
                    <td>
                        <a class="btn waves-effect waves-light btn-danger sa-warning btnExcluirItem" data-id='{{$item['id']}}'><i class="mdi mdi-delete"></i> @lang('buttons.general.delete')</a>
                        <a class="btn waves-effect waves-light btn-info btnEditNorma" data-id='{{$item['id']}}' ><input type="hidden" name="dados[]" id="dados{{$item['id']}}" value='{{JSON_encode($conteudoBotao)}}'><i class="mdi mdi-lead-pencil"></i> @lang('buttons.general.edit')</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>

@section('footer')
<!-- This is data table -->
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/dataTables/dataTables.rowReorder.min.js') }}"></script>

<script>
    var myTable;
    $(document).ready(function() {
        myTable = $('#itens').DataTable({
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
            dom: 'frt',
            rowReorder: false
        });

        $(document).on('click','#btnItemNorma', function(){
            
            var $inputsModal = $('#formItemNorma :input');
            $inputsModal.each(function() {
                    $(this).val('').prop('checked',false).selectpicker('refresh');
            });
            $('#normaAlteracaoId').val('');
            $('#modalEtapaFluxo').modal('show');

        });

        $('#itens').on( 'click', 'tbody tr td .btnExcluirItem', function () {
            deleteTR($(this).parent().parent());
        } );

    });

    function deleteTR(trDeletar){
        myTable.row( trDeletar ).remove().draw();
    }
</script>
@endsection