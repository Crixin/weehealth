
    <div class="modal"  id="modalEtapaFluxo" tabindex="-1" role="dialog" >
        <div class="modal-dialog modal-xl" role="document">
            
            <form action="#" onsubmit="validacao()"  id="formEtapaNorma" name="formEtapaNorma">
                <input type="hidden"  id="normaAlteracaoId">
                <input type="hidden"  id="idEtapaEdicao">
                <input type="hidden"  id="idfluxo" value="{{$etapa->id ?? ''}}">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">@lang('buttons.docs.etapa-fluxo.create')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div class="modal-body">
                        @component(
                            'docs::components.etapa-fluxo', 
                            [
                                'etapaEdit' => $etapa,
                                'nome'      => $etapa->nome ?? '',
                                'descricao' => $etapa->descricao ?? '',
                                'perfis'    => $perfis,
                                'status'    => $status,
                                'notificacoes' => $notificacoes,
                                'tiposAprovacao' => $tipoAprovacao,
                                'etapasRejeicao' => $etapasRejeicao
                            ]
                        )
                        @endcomponent
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('buttons.general.cancel')</button>
                    <button type="submit" id="btnSalvar" class="btn btn-primary">@lang('buttons.general.save')</button>
                    </div>
                </div>
                </form>
        </div>
    </div>
<script>
    var tr;
    $(document).ready(function(){
        $(document).on('click', '.btnEditEtapa', function(){
            let id = $(this).data('id');
            let etapa = $(this).data('etapa');
            let dados = $('#dados'+id).val();
            let obj = JSON.parse(dados);
            for( const key in obj){
                
                if(obj[key] == true || obj[key] == false){
                    $('#'+key).prop('checked', obj[key]);
                }
                $('#'+key).val('').val(obj[key]).selectpicker('refresh');
            }
            $('#normaAlteracaoId').val(id);
            $('#idEtapaEdicao').val(etapa);
            $('#modalEtapaFluxo').modal('show');

            tr= $(this).parent().parent();
        });
    });

    function validacao() {
        event.preventDefault();
        event.stopPropagation();
        enviaFormulario();
    }
    function enviaFormulario() {
        $('.odd').remove();
        var $inputs = $('#formEtapaNorma :input');
        var values = {};
        $inputs.each(function() {
            if(this.name != ''){
                 let valor = '';
                 switch (this.type) {
                     case 'checkbox':
                         valor = $(this).prop('checked');
                         break;
                    case 'text':
                         valor = $(this).val().replace(/ /g,'&nbsp;');
                         break; 
                    case 'select-one':
                         valor = $(this).val();
                         break;
                 }
                
                values[this.name] = valor;
            }
        });
        montaLinha(values);
    }

    function montaLinha(values)
    {
        let ordem = parseInt( $('#ordemHidden').val());
        var t = $('#dataTable-etapas').DataTable();
        
        if($('#normaAlteracaoId').val() == ''){
            values['id'] = '';
            values['ordem'] = ordem + 1;
            ordem += 1;
            $('#ordemHidden').val(ordem);
            
            var botaoCriacao  = montaBotao(ordem, values);
            //criar
            t.row.add(
                [
                    ordem, values.nome, values.descricao, botaoCriacao
                ]
            ).draw( false );
        }else{
            //editar
            values['ordem'] = $('#normaAlteracaoId').val();
            values['id'] =  parseInt( $('#idEtapaEdicao').val());
            let idBotao = $('#normaAlteracaoId').val();
            var botaoEdicao = montaBotao($('#normaAlteracaoId').val(), values);

            deleteTR(tr);
            t.row.add(
                [
                    idBotao, values.nome, values.descricao, botaoEdicao
                ]
            ).draw( false );
            $('#dados'+idBotao).val(JSON.stringify(values));
        }
        $('#modalEtapaFluxo').modal('hide');
    }

    function montaBotao(ordem, values)
    {
        var botao = '<a  class="btn waves-effect waves-light btn-danger sa-warning btnExcluirEtapa mr-1" data-id='+ordem+'><i class="mdi mdi-delete"></i> Excluir</a>'; 
            botao += '<a  class="btn waves-effect waves-light btn-info btnEditEtapa" data-id='+ordem+'><input type="hidden" name="dados[]" id="dados'+ordem+'" value='+JSON.stringify(values)+'><i class="mdi mdi-lead-pencil"></i> Editar</a>';
        return botao;
    }
</script>