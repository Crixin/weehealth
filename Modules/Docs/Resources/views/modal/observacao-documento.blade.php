<div class="modal fade" id="modal-observacao-documento" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="mySmallModalLabel">Observações do documento</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            
            <div class="modal-body"> 
                <div class="row">
                    <div class="chat-box justify-content-center text-center" style="width: 100%;">
                        <!-- Listagem de Observações -->
                        <ul class="chat-list container-fluid" id="lista-observacoes-documento" style="width: 100%;">
                            
                        </ul>
                    </div>  
                </div>
            </div>

            <div class="modal-footer">
                {!! Form::open(['method' => 'POST', 'id' => 'salvar-modal-observacoes']) !!}
                    <div class="row">
                        <div class="form-group">
                            {{ Form::hidden('documento_id', $idDocumento, ['id' => 'document_id']) }}
                            <div class="col-md-12 control-label font-bold">
                                {!! Form::label('', 'Observação:') !!}
                            </div>
                            <div class="col-md-12">
                                {!! Form::textarea('observacao_documento', null, ['class' => 'form-control', 'required' => 'required', 'id' => 'observacao_documento', 'rows' => '5']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="pull-right">
                        <button class="btn btn-success waves-effect" id="btn-save-obs">Gravar</button>
                        <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal">Fechar</button>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){

        $('.observacoes-documento').click(function(){
            $('#modal-observacao-documento').modal('show');
            loadObservacaoModal();
        });


        $('#salvar-modal-observacoes').submit(function (e) { 
            e.preventDefault();
            let values = ($(this).serializeArray());
            let submitValues = {}

            values.forEach(element => {
                if (element.name != "_token") {
                    submitValues[element.name] = element.value
                }
            });

            ajaxMethod("POST", "{{URL::route('docs.observacao-documento.salvar')}}", submitValues).then(resp => {
                if (resp.success) {
                    loadObservacaoModal();
                    $("#observacao_documento").val("");
                    showToast('Sucesso!', 'Observação inserida!', 'success');
                } else {
                    showToast('Falha!', 'Observação não inserida! Atualize a página e tente novamente', 'error');
                }
            }).catch(error => {
                showToast('Falha!', 'Observação não inserida! Atualize a página e tente novamente', 'error');
            });

        });
    });

    function loadObservacaoModal()
    {
        ajaxMethod("GET", "{{route('docs.observacao-documento.buscar', $idDocumento)}}").then(resp => {
            if (resp.success) {
                $("#lista-observacoes-documento").empty();
                
                let linhas = "";
                resp.data.forEach(function(obs, key) {
                    var event = new Date(obs.created_at);
                    var year = event.getFullYear(), month = event.getMonth() + 1, date1 = event.getDate(), hour = event.getHours(), minutes = event.getMinutes();

                    var dateF = hour +":"+ minutes +" "+ date1 +"/"+ month +"/" + year;

                    if(key % 2 == 0) {
                        linhas += 
                        `<li>
                            <div class="chat-content text-left">
                                <h5> ${obs.core_users.name} </h5>
                                <div class="box bg-light-info"> ${obs.observacao} </div>
                                <div class="chat-time"> ${dateF}</div>
                            </div>
                        </li>
                        `;
                    } else {
                        linhas += 
                        `<li class="odd">
                            <div class="chat-content">
                                <h5> ${obs.core_users.name} </h5>
                                <div class="box bg-light-inverse"> ${obs.observacao} </div>
                                <div class="chat-time"> ${dateF}</div>
                            </div>
                        </li>
                        `;
                    }
                });
                $("#lista-observacoes-documento").append(linhas);
            } else {
                showToast('Falha!', 'Erro ao carregar as observações! Atualize a página e tente novamente', 'error');
            }
        }).catch(error => {
            showToast('Falha!', 'Erro ao carregar as observações! Atualize a página e tente novamente', 'error');
        });

    }

</script>