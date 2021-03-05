<div class="modal"  id="modalTipoDocumentoSetor" tabindex="-1" role="dialog" >
    <div class="modal-dialog modal-lg" role="document">
        
        <form action="#" onsubmit="validacao()"  id="formTipoDocumentoSetor" name="formTipoDocumentoSetor">
            <input type="hidden"  id="tipoDocumentoId">
            <input type="hidden"  id="idTipoDocumentoSetor">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">@lang('buttons.docs.tipo-documento-setor.create')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    
                    
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group{{ $errors->has('setor') ? ' has-error' : '' }}">
                                    <label class="control-label">Setor</label>
                                    <select name="setor" class="form-control selectpicker"  data-live-search="true"  data-actions-box="true" id="setor"  required>
                                            <option value="" >{{__('components.selectepicker-default')}}</option>
                                        @foreach ($setores as $key => $setor)
                                            <option value="{{$setor}}">{{$setor}}</option>    
                                        @endforeach
                                    </select>
                                    @if ($errors->has('setor'))
                                        <br/>
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('setor') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row p-t-20">
                            <div class="col-md-12">
                                <div class="form-group required{{ $errors->has('numero') ? ' has-error' : '' }}">
                                {!! Form::label('numero', 'Último Código', ['class' => 'control-label']) !!}
                                {!! Form::number('numero',$numero ?? null, ['class' => 'form-control', 'required' => 'required', 'min' => '0']) !!}
                                <small class="text-danger">{{ $errors->first('numero') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('buttons.general.cancel')</button>
                <button type="submit" id="btnSalvar" class="btn btn-primary">@lang('buttons.general.save')</button>
                </div>
            </div>
            </form>
    </div>
</div>
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<script>
    var tr;
    $(document).ready(function(){
        $(document).on('click', '.btnEdit', function(){
            let id = $(this).data('id');
            let etapa = $(this).data('etapa');
            let dados = $('#dados'+id).val();
            let obj = JSON.parse(dados);
            for( const key in obj){
                $('#'+key).val('').val(obj[key]).selectpicker('refresh');
            }
            $('#tipoDocumentoId').val(id);
            $('#idTipoDocumentoSetor').val(etapa);
            $('#modalTipoDocumentoSetor').modal('show');

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
        var $inputs = $('#formTipoDocumentoSetor :input');
        var values = {};
        $inputs.each(function() {
            if(this.name != ''){
                values[this.name] = $(this).val();
            }
        });
        montaLinha(values);
    }

    function montaLinha(values)
    {
        console.log(values);
        let ordem = parseInt( $('#ordemHidden').val());
        var t = $('#itens').DataTable();
        
        if($('#tipoDocumentoId').val() == ''){
            values['id'] = '';
            values['ordem'] = ordem + 1;
            ordem += 1;
            $('#ordemHidden').val(ordem);
            
            var botaoCriacao  = montaBotao(ordem, values);
            //criar
            t.row.add(
                [
                    ordem, values.setor, values.numero, botaoCriacao
                ]
            ).draw( false );
        }else{
            //editar
            values['ordem'] =  $('#tipoDocumentoId').val();
            values['id'] =  parseInt( $('#idTipoDocumentoSetor').val());
            let idBotao = $('#tipoDocumentoId').val();
            var botaoEdicao = montaBotao(parseInt( $('#tipoDocumentoId').val()), values);

            deleteTR(tr);
            t.row.add(
                [
                    idBotao, values.setor, values.numero, botaoEdicao
                ]
            ).draw( false );
            $('#dados'+idBotao).val(JSON.stringify(values));
        }
        $('#modalTipoDocumentoSetor').modal('hide');
    }

    function montaBotao(ordem, values)
    {
        var botao = '<a  class="btn waves-effect waves-light btn-danger sa-warning btnExcluirItem mr-1" data-id='+ordem+'><i class="mdi mdi-delete"></i> Excluir</a>'; 
            botao += "<a class='btn waves-effect waves-light btn-info btnEdit' data-id='"+ordem+"'><input type='hidden' name='dados[]' id='dados"+ordem+"' value='"+JSON.stringify(values)+"'><i class='mdi mdi-lead-pencil'></i> Editar</a>";
        return botao;
    }

</script>