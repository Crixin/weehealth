<div class="modal fade" id="modalCopiasControladas" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLabel">Gerenciando cópias controladas do documento: <span class="text-themecolor">{{ $documento->nome }}</span></h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>                        
            <div class="modal-body">
                <div class="row">

                    <div class="col-md-12">
                        <h4>Novo registro de cópia controlada</h4>
                        <form action="{{route('docs.copia-controlada.salvar')}}" method="POST" id="formCopiaControlada" name="formCopiaControlada">
                            {{ csrf_field() }}
                            <input type="hidden" name="idDocumento" id="idDocumento" value="{{$documento->id}}">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <div class="col-md-10 control-label font-bold">
                                            {!! Form::label('numeroDeCopias', 'Número', ['class' => 'control-label']) !!}
                                        </div>
                                        <div class="col-md-12">
                                            {!! Form::number('numeroDeCopias', null, ['class' => 'form-control', 'max' => '999', 'id' => 'numeroDeCopias', 'required' => 'required']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="col-md-10 control-label font-bold">
                                            {!! Form::label('revisaoDasCopias', 'Revisão', ['class' => 'control-label']) !!}
                                        </div>
                                        <div class="col-md-12">
                                            {!! Form::text('revisaoDasCopias', null, ['class' => 'form-control', 'maxlength' => '10', 'id' => 'revisaoDasCopias', 'required' => 'required']) !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="form-group">
                                        <div class="col-md-10 control-label font-bold">
                                            {!! Form::label('setorDasCopias', 'Setor', ['class' => 'control-label']) !!}
                                        </div>
                                        <div class="col-md-12">
                                            {!! Form::text('setorDasCopias', null, ['class' => 'form-control', 'maxlength' => '35', 'id' => 'setorDasCopias', 'required' => 'required']) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-9">
                                    <div class="form-group required">
                                        <div class="col-md-10 control-label font-bold">
                                            {!! Form::label('responsavel', 'Responsável pela Substituição') !!}
                                        </div>
                                        <div class="col-md-12">
                                            <select id="responsavel" name="responsavel" class="form-control select2 m-b-10" style="width: 100%" data-placeholder="Digite..." required>
                                                    <option value="">Nada selecionado</option>
                                                @foreach ($usuarios as $id => $nome)
                                                    <option value="{{ $id }}">{{ $nome }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-block btn-info waves-effect pull-right" id="btnSalvarCopiaControlada" style="margin-top: 20%">Salvar Registro</button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div id="mensagem-copia-controlada"></div>
                                </div>
                            </div>
                        </form>
                        <hr style="border-top: 2px solid darkgray">
                    </div>
                    <div class="col-md-12">
                        <h4>Listagem de cópias controladas do documento</h4>
                        <div class="table-responsive">
                            <table class="table table-condensed" >
                                <thead>
                                    <tr>
                                        <th class="text-nowrap ">Nº de Cópias</th>
                                        <th class="text-nowrap ">Revisão</th>
                                        <th class="text-nowrap ">Setor</th>
                                        <th class="text-nowrap ">Responsável</th>
                                        <th class="text-nowrap text-center">Ações</th>
                                    </tr>
                                </thead>
                                <tbody id="listagemCopiaControlada">
                                    
                                </tbody>
                            </table>
                        </div>
                        
                    </div>
                    
                    

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn  btn-secondary waves-effect" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<script>
$(document).ready(function() {

    $('#btnGerenciarCopiaControlada').on('click', function(){
        $('#modalCopiasControladas').modal('show');
        buscaCopiaControlada();
    });

    $("#formCopiaControlada").submit(function(e){
        event.preventDefault();
        var form = $(this);
        var formData = new FormData($(this)[0]);
        var url = form.attr('action');
        $.ajax({  
            type: "POST",  
            url: url,  
            data: formData,
            async: false,
            cache: false,
            contentType: false,
            processData: false,
            success: function(data) {
                showToast('Sucesso!', 'O registro de cópia controlada foi salvo.', 'success');
                $('#formCopiaControlada')[0].reset();
                buscaCopiaControlada();
            },
            error: function(erro){
                console.log(erro);
            }
        });  
    });

    // Exclusão
    $(document).on("click", "#btn-delete-copia-controlada", function() {
        let id = $(this).data('copia-controlada-id');
        let deleteIt = swal2_warning("Essa ação é irreversível!");
        let obj = {'id': id};

        deleteIt.then(resolvedValue => {
            ajaxMethod('POST', "{{ URL::route('docs.copia-controlada.deletar') }}", obj).then(response => {
                if(response.response != 'erro') {
                    swal2_success_not_reload("Excluído!", "Cópia controlada excluída com sucesso.");
                    buscaCopiaControlada();
                } else {
                    swal2_alert_error_not_reload("Tivemos um problema ao excluir a cópia controlada.");
                }
            }, error => {
                console.log(error);
            });
        }, error => {
            swal.close();
        });
    });
});

function buscaCopiaControlada()
{
    let id  = $('#idDocumento').val();
    let obj = {'documento_id': id};
    ajaxMethod('POST', "{{ URL::route('docs.copia-controlada.buscar') }}", obj).then(ret => {

        if(ret.response == 'erro') {
            swal2_alert_error_support("Tivemos um problema ao buscar a cópia controlada.");
        }
        let dados = ret.data;
        let linha = '';
        $('#listagemCopiaControlada').empty();
        for (let index = 0; index < dados.length; index++) {
            const element = dados[index];
            linha += '<tr>';
            linha += '<td class="text-nowrap ">'+element.numero_copias+'</td>';
            linha += '<td class="text-nowrap ">'+element.revisao+'</td>';
            linha += '<td class="text-nowrap ">'+element.setor+'</td>';
            linha += '<td class="text-nowrap ">'+element.user+'</td>';
            linha += '<td class="text-nowrap text-center"><button type="button" id="btn-delete-copia-controlada" class="btn btn-rounded btn-danger" data-copia-controlada-id="'+element.id+'"> <i class="fa fa-close"></i> </button></td>';
            linha += '</tr>';
            
        }
        $('#listagemCopiaControlada').append(linha); 
        
    }, error => {
        console.log(error);
    });
}
</script>