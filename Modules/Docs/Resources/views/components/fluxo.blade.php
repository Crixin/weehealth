<div class="form-body">
    <div class="row p-t-20">
        <div class="col-md-6">
            <div class="form-group required{{ $errors->has('nome') ? ' has-error' : '' }}">
                {!! Form::label('nome', 'Nome', ['class' => 'control-label']) !!}
                {!! Form::text('nome', $nome, ['class' => 'form-control', 'required' => 'required']) !!}
                <small class="text-danger">{{ $errors->first('nome') }}</small>
            </div>
        </div>
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
            <div class="form-group {{ $errors->has('versao') ? ' has-error' : '' }}">
                {!! Form::label('versao', 'Versão') !!}
                {!! Form::text('versao', $versao, ['class' => 'form-control', 'readonly' => true, 'disabled'=> true]) !!}
                
                <small class="text-danger">{{ $errors->first('versao') }}</small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <div class="checkbox required{{ $errors->has('ativo') ? ' has-error' : '' }}">
                    {!! Form::label('status', 'Status', ['class' => 'control-label']) !!}
                    <td class="text-center text-nowrap">
                        <div class="switch">
                            <label for="ativo">Inativo
                            {!! Form::checkbox('ativo', '1', !empty($fluxoEdit) ?  $fluxoEdit->ativo : true, ['id' => 'ativo', 'class'=> 'switch-elaborador']) !!}
                            <span class="lever switch-col-light-blue"></span>Ativo
                            </label>
                        </div>
                    </td>    
                </div>
                <small class="text-danger">{{ $errors->first('ativo') }}</small>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group required{{ $errors->has('grupo') ? ' has-error' : '' }}">
                {!! Form::label('grupo', 'Grupo', ['class' => 'control-label']) !!}
            
                {!! Form::select('grupo', $grupos, !empty($fluxoEdit) ?  $fluxoEdit->grupo_id : null, ['id' => 'grupo', 'class' => 'form-control selectpicker ', 'required' => true, 'placeholder' => __('components.selectepicker-default') ]) !!}
                <small class="text-danger">{{ $errors->first('grupo') }}</small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group required{{ $errors->has('perfil') ? ' has-error' : '' }}">
                {!! Form::label('perfil', 'Perfil', ['class' => 'control-label']) !!}
            
                {!! Form::select('perfil', $perfis, !empty($fluxoEdit) ?  $fluxoEdit->perfil_id : null, ['id' => 'perfil', 'class' => 'form-control selectpicker ', 'required' => true, 'placeholder' => __('components.selectepicker-default') ]) !!}
                <small class="text-danger">{{ $errors->first('perfil') }}</small>
            </div>
        </div>
    </div>
</div>

<legend>@lang('page_titles.docs.etapa-fluxo.index')</legend>
<hr>
<div class="table-responsive m-t-40">
    <button type="button" id="btnEtapaFluxo" class="btn btn-info"><i class="mdi mdi-pencil"></i>&nbsp;@lang('buttons.docs.etapa-fluxo.create')</button>
    <table id="dataTable-etapas"  class="display " cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>Ordem</th>
                <th>Nome</th>
                <th>Descrição</th>
                <th>Controle</th>        
            </tr>
        </thead>
        <tbody id="itemEtapas">
            @foreach ($etapas ?? [] as $etapa)
                @php
                    $conteudoBotao = 
                    [
                        "nome"=> $etapa->nome,
                        "descricao"=>$etapa->descricao,
                        "status"=>$etapa->status_id,
                        "perfil"=>$etapa->perfil_id,
                        "permitirAnexo"=>$etapa->permitir_anexo,
                        "obrigatoria"=>$etapa->obrigatorio,
                        "enviarNotificacao"=>$etapa->enviar_notificacao,
                        "notificacao"=>$etapa->notificacao_id,
                        "comportamentoCriacao"=>$etapa->comportamento_criacao,
                        "comportamentoEdicao"=>$etapa->comportamento_edicao,
                        "comportamentoAprovacao"=>$etapa->comportamento_aprovacao,
                        "comportamentoVizualizacao"=>$etapa->comportamento_visualizacao,
                        "comportamentoDivulgacao"=>$etapa->comportamento_divulgacao,
                        "comportamentoTreinamento"=>$etapa->comportamento_treinamento,
                        "tipoAprovacao"=>$etapa->tipo_aprovacao_id,
                        "etapaRejeicao"=>$etapa->etapa_rejeicao_id,
                        "listaPresenca"=>$etapa->exigir_lista_presenca,
                        "ordem" => $etapa->ordem,
                        "id" => $etapa->id
                    ];
                @endphp
                <tr>
                    <td data-id="{{$etapa->ordem}}">{{$etapa->ordem}}</td>
                    <td>{{$etapa->nome}}</td>
                    <td>{{$etapa->descricao}}</td>
                    <td>
                        <a class="btn waves-effect waves-light btn-danger sa-warning btnExcluirEtapa" data-id='{{$etapa->ordem}}'><i class="mdi mdi-delete"></i> @lang('buttons.general.delete')</a>
                        <a class="btn waves-effect waves-light btn-info btnEditEtapa" data-id='{{$etapa->ordem}}' data-etapa='{{$etapa->id}}'><input type="hidden" name="dados[]" id="dados{{$etapa->ordem}}" value='{{JSON_encode($conteudoBotao)}}'><i class="mdi mdi-lead-pencil"></i> @lang('buttons.general.edit')</a>
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
<!-- end - This is for export functionality only -->
<script>
    var myTable;
    $(document).ready(function() {
        myTable = $('#dataTable-etapas').DataTable({
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
            rowReorder: true
        });

        $(document).on('click','#btnEtapaFluxo', function(){
            
            var $inputsModal = $('#formEtapaNorma :input');
            $inputsModal.each(function() {
                    $(this).val('').prop('checked',false).selectpicker('refresh');
            });
            $('#normaAlteracaoId').val('');

            
            //monta etapa rejeicao
            let linhaRejeicao = '<option value="">Nada selecionado</option>';
            let dadosGrid = document.getElementsByName('dados[]');
            dadosGrid.forEach(element => {
                let aux = JSON.parse(element.value);

                let valueMontaRejeicao = $('#idEtapaEdicao').val() == '' && $('#idfluxo') == '' ? aux.ordem : aux.id;
                console.log(valueMontaRejeicao);
                let selectMontaRejeicao = $('#idEtapaEdicao').val() == '' ? '' :  (aux.id == $('#etapaRejeicaoHidden').val() ? 'selected="selected"': '' );
                linhaRejeicao += '<option '+selectMontaRejeicao+'  value="'+ valueMontaRejeicao +'">'+aux.nome+'</option>';
                
            });
            //console.log(linhaRejeicao);
            $('#etapaRejeicao').empty().append(linhaRejeicao).selectpicker("refresh");


            $('#modalEtapaFluxo').modal('show');
        });

        $('#dataTable-etapas').on( 'click', 'tbody tr td .btnExcluirEtapa', function () {
            deleteTR($(this).parent().parent());
        } );

        
        /*teste reorder*/
        myTable.on( 'row-reorder', function ( e, diff, edit ) {
            for ( var i=0, ien=diff.length ; i<ien ; i++ ) {
                $(diff[i].node).addClass("reordered");
            }
        } );

        myTable.on( 'row-reorder', function ( e, details, changes ) {
           
            details.forEach(element => {
                let id = element.node.childNodes[1].dataset.id;
                let valorOld = element.oldData;
                let valorNew = element.newData;

                console.log(valorOld);
                console.log(valorNew);

                let valorBotao = JSON.parse($('#dados'+id).val());
                valorBotao['ordem'] = parseInt(valorNew);
                $('#dados'+id).val(JSON.stringify(valorBotao));
            });
        } );


        $(document).on('click','.sweet-overlay', function(){
            swal.close();
        });
        
    });

    $(document).keyup(function(e) { 
        if (e.keyCode === 27) 
            swal.close();
    }); 

    function deleteTR(trDeletar){
        myTable.row( trDeletar ).remove().draw();
    }

    function msgConfirmacao() {
        event.preventDefault();
        event.stopPropagation();
        let gerarVersaoFluxo = swal2_warning("Você deseja gerar uma nova versão do fluxo", "Sim, Gerar", '#DD6B55', 'Gerar nova versão do fluxo ?', 'Não, Não Gerar');
        gerarVersaoFluxo.then(resolvedValue => {
            $('#novaVersaoFluxo').val(new Boolean(true));
            $('#formFluxoEdit').submit();
        }, error => {
            $('#novaVersaoFluxo').val(new Boolean(false));
            swal.close();
            $('#formFluxoEdit').submit();
        });
    }
</script>
@endsection