<div class="form-body">
    <h5>Informações da Etapa</h5>
    <hr>
    <div class="row">
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
            <div class="form-group required{{ $errors->has('status') ? ' has-error' : '' }}">
                {!! Form::label('status', 'Status', ['class' => 'control-label']) !!}
                {!! Form::select('status', $status, !empty($etapaEdit) ?  $etapaEdit->status_id : null, ['id' => 'status', 'class' => 'form-control selectpicker ', 'required' => true, 'placeholder' => __('components.selectepicker-default') ]) !!}
                <small class="text-danger">{{ $errors->first('status') }}</small>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="form-group required{{ $errors->has('perfil') ? ' has-error' : '' }}">
                {!! Form::label('perfil', 'Perfil', ['class' => 'control-label']) !!}
            
                {!! Form::select('perfil', $perfis, !empty($etapaEdit) ?  $etapaEdit->perfil_id : null, ['id' => 'perfil', 'class' => 'form-control selectpicker ', 'required' => true, 'placeholder' => __('components.selectepicker-default') ]) !!}
                <small class="text-danger">{{ $errors->first('perfil') }}</small>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <div class="checkbox{{ $errors->has('permitirAnexo') ? ' has-error' : '' }}">
                    <label class="control-label">Permitir Anexos</label>
                                    
                    <td class="text-center text-nowrap">
                        <div class="switch">
                            <label>Não
                                {!! Form::checkbox('permitirAnexo', '1', !empty($etapaEdit) ?  $etapaEdit->permitir_anexo : false, ['id' => 'permitirAnexo', 'class'=> 'switch-elaborador']) !!}<span class="lever switch-col-light-blue"></span>Sim
                            </label>
                        </div>
                    </td>
                <small class="text-danger">{{ $errors->first('permitirAnexo') }}</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <div class="checkbox{{ $errors->has('obrigatoria') ? ' has-error' : '' }}">
                    <label class="control-label">Obrigatória</label>
                                    
                    <td class="text-center text-nowrap">
                        <div class="switch">
                            <label>Não
                                {!! Form::checkbox('obrigatoria', '1', !empty($etapaEdit) ?  $etapaEdit->obrigatoria : false, ['id' => 'obrigatoria', 'class'=> 'switch-elaborador']) !!}<span class="lever switch-col-light-blue"></span>Sim
                            </label>
                        </div>
                    </td>
                <small class="text-danger">{{ $errors->first('obrigatoria') }}</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <div class="checkbox{{ $errors->has('enviarNotificacao') ? ' has-error' : '' }}">
                    <label class="control-label">Enviar Notificações</label>
                    <td class="text-center text-nowrap">
                        <div class="switch">
                            <label>Não
                                {!! Form::checkbox('enviarNotificacao', '1', !empty($etapaEdit) ?  $etapaEdit->enviar_notificacao : true, ['id' => 'enviarNotificacao', 'class'=> 'switch-elaborador']) !!}<span class="lever switch-col-light-blue"></span>Sim
                            </label>
                        </div>
                    </td>
                <small class="text-danger">{{ $errors->first('enviarNotificacao') }}</small>
                </div>
            </div>
        </div>
        <div class="col-md-6 div-notificacao">
            <div class="form-group required{{ $errors->has('notificacao') ? ' has-error' : '' }}">
                {!! Form::label('notificacao', 'Notificação', ['class' => 'control-label', 'id' => 'iconeNotificacao']) !!}
            
                {!! Form::select('notificacao', $notificacoes, !empty($etapaEdit) ?  $etapaEdit->notificacao_id : null, ['id' => 'notificacao', 'class' => 'form-control selectpicker ', 'required' => true, 'placeholder' => __('components.selectepicker-default') ]) !!}
                <small class="text-danger">{{ $errors->first('notificacao') }}</small>
            </div>
        </div>
    </div>
    <h5>Comportamento Editor</h5>
    <hr>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <div class="checkbox{{ $errors->has('comportamentoCriacao') ? ' has-error' : '' }}">
                    <label class="control-label">Criação</label>
                                    
                    <td class="text-center text-nowrap">
                        <div class="switch">
                            <label>Não
                                {!! Form::checkbox('comportamentoCriacao', '1', !empty($etapaEdit) ?  $etapaEdit->comportamento_criacao : false, ['id' => 'comportamentoCriacao', 'class'=> 'switch-elaborador']) !!}<span class="lever switch-col-light-blue"></span>Sim
                            </label>
                        </div>
                    </td>
                <small class="text-danger">{{ $errors->first('comportamentoCriacao') }}</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <div class="checkbox{{ $errors->has('comportamentoEdicao') ? ' has-error' : '' }}">
                    <label class="control-label">Edição</label>
                    <td class="text-center text-nowrap">
                        <div class="switch">
                            <label>Não
                                {!! Form::checkbox('comportamentoEdicao', '1', !empty($etapaEdit) ?  $etapaEdit->comportamento_edicao : false, ['id' => 'comportamentoEdicao', 'class'=> 'switch-elaborador']) !!}<span class="lever switch-col-light-blue"></span>Sim
                            </label>
                        </div>
                    </td>
                    <small class="text-danger">{{ $errors->first('comportamentoEdicao') }}</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <div class="checkbox{{ $errors->has('comportamentoVizualizacao') ? ' has-error' : '' }}">
                    <label class="control-label">Visualização</label>
                    <td class="text-center text-nowrap">
                        <div class="switch">
                            <label>Não
                                {!! Form::checkbox('comportamentoVizualizacao', '1', !empty($etapaEdit) ?  $etapaEdit->comportamento_visualizacao : false, ['id' => 'comportamentoVizualizacao', 'class'=> 'switch-elaborador']) !!}<span class="lever switch-col-light-blue"></span>Sim
                            </label>
                        </div>
                    </td>
                    <small class="text-danger">{{ $errors->first('comportamentoVizualizacao') }}</small>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <div class="checkbox{{ $errors->has('comportamentoAprovacao') ? ' has-error' : '' }}">
                    <label class="control-label">Aprovação</label>
                                    
                    <td class="text-center text-nowrap">
                        <div class="switch">
                            <label>Não
                                {!! Form::checkbox('comportamentoAprovacao', '1', !empty($etapaEdit) ?  $etapaEdit->comportamento_aprovacao : false, ['id' => 'comportamentoAprovacao', 'class'=> 'switch-elaborador']) !!}<span class="lever switch-col-light-blue"></span>Sim
                            </label>
                        </div>
                    </td>
                    <small class="text-danger">{{ $errors->first('comportamentoAprovacao') }}</small>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="form-group">
                <div class="checkbox{{ $errors->has('comportamentoDivulgacao') ? ' has-error' : '' }}">
                    <label class="control-label">Divulgação</label>
                    <td class="text-center text-nowrap">
                        <div class="switch">
                            <label>Não
                                {!! Form::checkbox('comportamentoDivulgacao', '1', !empty($etapaEdit) ?  $etapaEdit->comportamento_divulgacao : false, ['id' => 'comportamentoDivulgacao', 'class'=> 'switch-elaborador']) !!}<span class="lever switch-col-light-blue"></span>Sim
                            </label>
                        </div>
                    </td>
                    <small class="text-danger">{{ $errors->first('comportamentoDivulgacao') }}</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <div class="checkbox{{ $errors->has('comportamentoTreinamento') ? ' has-error' : '' }}">
                    <label class="control-label">Treinamento</label>
                    <td class="text-center text-nowrap">
                        <div class="switch">
                            <label>Não
                                {!! Form::checkbox('comportamentoTreinamento', '1', !empty($etapaEdit) ?  $etapaEdit->comportamento_treinamento : false, ['id' => 'comportamentoTreinamento', 'class'=> 'switch-elaborador']) !!}<span class="lever switch-col-light-blue"></span>Sim
                            </label>
                        </div>
                    </td>
                    <small class="text-danger">{{ $errors->first('comportamentoTreinamento') }}</small>
                </div>
            </div>
        </div>
    </div>
    <h5 >Complemento</h5>
    <hr>
    <div class="row">
        <div class="col-md-6 div-aprovacao hide">
            <div class="form-group required {{ $errors->has('tipoAprovacao') ? ' has-error' : '' }}">
                {!! Form::label('tipoAprovacao', 'Tipo Aprovação', ['class' => 'control-label']) !!}
            
                {!! Form::select('tipoAprovacao', $tiposAprovacao, !empty($etapaEdit) ?  $etapaEdit->tipo_aprovacao_id : null, ['id' => 'tipoAprovacao', 'class' => 'form-control selectpicker', 'required' => true, 'placeholder' => __('components.selectepicker-default') ]) !!}
                <small class="text-danger">{{ $errors->first('tipoAprovacao') }}</small>
            </div>
        </div>
        <div class="col-md-6 div-aprovacao hide">
            <div class="form-group {{ $errors->has('etapaRejeicao') ? ' has-error' : '' }}">
                {!! Form::label('etapaRejeicao', 'Etapa Após Rejeição') !!}
            
                {!! Form::select('etapaRejeicao', $etapasRejeicao, !empty($etapaEdit) ?  $etapaEdit->etapa_rejeicao_id : null, ['id' => 'etapaRejeicao', 'class' => 'form-control selectpicker ', 'placeholder' => __('components.selectepicker-default') ]) !!}
                <small class="text-danger">{{ $errors->first('etapaRejeicao') }}</small>
            </div>
        </div>
        <div class="col-md-6 div-lista-presenca hide">
            <div class="form-group">
                <div class="checkbox{{ $errors->has('listaPresenca') ? ' has-error' : '' }}">
                    <label class="control-label">Lista de Presença</label>
                    <td class="text-center text-nowrap">
                        <div class="switch">
                            <label>Não
                                {!! Form::checkbox('listaPresenca', '1', !empty($etapaEdit) ?  $etapaEdit->listaPresenca : false, ['id' => 'listaPresenca', 'class'=> 'switch-elaborador']) !!}<span class="lever switch-col-light-blue"></span>Sim
                            </label>
                        </div>
                    </td>
                    <small class="text-danger">{{ $errors->first('listaPresenca') }}</small>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<script>
    $(document).ready(function() {

        $(document).on('show.bs.modal', '.modal', function () {
            notificacao();
            tipoAprovacao();
            treinamento();
        });

        $('#enviarNotificacao').on('change',function(){
            notificacao();
        });
        
        $('#comportamentoAprovacao').on('change', function () {
            tipoAprovacao();
        });
        
        $('#comportamentoTreinamento').on('change', function () {
            treinamento();
        });

    });

    function notificacao()
    {
        if($('#enviarNotificacao').prop('checked') == true){
            $("#notificacao").prop("disabled", false);
            $("#notificacao").selectpicker("refresh");
            $(".div-notificacao").show();
        }else{
            $("#notificacao").prop("disabled", true);
            $(".div-notificacao").hide();
        }
    }

    function tipoAprovacao()
    {
        if ($("#comportamentoAprovacao").is(':checked')) {
            $("#tipoAprovacao").prop("disabled", false);
            $("#etapaRejeicao").prop("disabled", false);
            $("#tipoAprovacao,#etapaRejeicao").selectpicker("refresh");
            $(".div-aprovacao").show();
        } else {
            $("#tipoAprovacao").prop("disabled", true);
            $("#etapaRejeicao").prop("disabled", true);
            $(".div-aprovacao").hide();
        }
    }

    function treinamento()
    {
        if($('#comportamentoTreinamento').is(':checked')){
            $(".div-lista-presenca").show();
        } else {
            $(".div-lista-presenca").hide();
        }
    }
</script>
