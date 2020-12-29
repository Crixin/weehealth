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
    <table id="example" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Id</th>
                <th>Descricao</th>
                <th>Checklist</th>           
            </tr>
        </thead>
        <tbody>
            
        </tbody>
    </table>
</div>
@section('footer')


<link rel="stylesheet" href="{{ asset('plugins/datatables-edit/css/jquery.dataTables.css') }}" />
<link rel="stylesheet" href="{{ asset('plugins/datatables-edit/css/buttons.dataTables.css') }}" />
<link rel="stylesheet" href="{{ asset('plugins/datatables-edit/css/select.dataTables.css') }}" />
<link rel="stylesheet" href="{{ asset('plugins/datatables-edit/css/responsive.dataTables.css') }}" />

<script src="{{ asset('plugins/bootstrap/js/bootstrap.min.js') }}"></script>

<script src="{{ asset('plugins/datatables-edit/js/jquery.dataTables.js') }}" ></script>
<script src="{{ asset('plugins/datatables-edit/js/dataTables.buttons.js') }}" ></script>
<script src="{{ asset('plugins/datatables-edit/js/dataTables.select.js') }}" ></script>
<script src="{{ asset('plugins/datatables-edit/js/dataTables.responsive.js') }}" ></script>
<script src="{{ asset('plugins/datatables-edit/dataTables.altEditor.free.js') }}"></script>
<script>
    var itens = {!!json_encode($itens)!!};
    var objeto = {};
    var array = [];
    for(var i=0;i<itens.length;i++){
        objeto = {
            0 : itens[i][0],
            1 : itens[i][1],
            2 : itens[i][2],
        }
        array.push(objeto);
    }
    var arrayDataTable = array;
    $('#arrayDataTable').val(JSON.stringify(array));
    
    $(document).ready(function() {
        var dataSet = {!!json_encode($itens)!!};
        var columnDefs = [             
        {
            title: "Id",
            type: "text",
            required: true,
            unique: true
        }, {
            title: "Descrição",
            type: "textarea",
            required: true,
        },{
            title: "CheckList",
            type: "textarea"
        }
        
        ];
        console.log('passou');

        var myTable;

        myTable = $('#example').DataTable({
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
                },
                "altEditor" : {
                    "modalClose" : "Cancelar",
                    "edit" : {
                        "title" : "Alterar",
                        "button" : "Alterar"
                    },
                    "add" : {
                        "title" : "Novo item da norma",
                        "button" : "Criar"
                    },
                    "delete" : {
                        "title" : "Deletar",
                        "button" : "Deletar"
                    },
                    "success" : "Sucesso.",
                    "error" : {
                        "message" : "Favor verificar os erros.",
                        "label" : "Erros",
                        "responseCode" : "Response code:",
                        "required" : "Valor Obrigatório",
                        "unique" : "Valor Duplicado"
                    }
                }
            },
            "sPaginationType": "full_numbers",
            data: dataSet,
            columns: columnDefs,
            dom: 'Bfrtip',        // Needs button container
            select: 'single',
            responsive: true,
            altEditor: true,     // Enable altEditor
            buttons: [
                {
                text: 'Novo',
                name: 'add'        // do not change name
                },
                {
                extend: 'selected', // Bind to Selected row
                text: 'Editar',
                name: 'edit'        // do not change name
                },
                {
                extend: 'selected', // Bind to Selected row
                text: 'Deletar',
                name: 'delete'      // do not change name
                }
            ]
        });
    });

</script>

@endsection