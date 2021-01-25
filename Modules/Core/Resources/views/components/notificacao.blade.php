<div class="form-body">
    <div class="row p-t-20">
        <div class="col-md-12">
            <div class="alert alert-info">
                <span class="float-left"><b>Exemplos de Tags disponíveis:</b></span>
                <br>
                <ul class="float-left mr-5">
                    <li>DATA_ELABORACAO       <span class="text-muted">- Tag: &ltDATA_ELABORACAO&gt</span></li>
                    <li>ELABORADOR            <span class="text-muted">- Tag: &ltELABORADOR&gt</span></li>
                    <li>APROVADOR             <span class="text-muted">- Tag: &ltAPROVADOR&gt</span></li>
                    <li>DATA_REVISAO          <span class="text-muted">- Tag: &ltDATA_REVISAO&gt</span></li>
                    <li>VERSAO                <span class="text-muted">- Tag: &ltVERSAO&gt</span></li>
                </ul>
                <ul>
                    <li>CODIGO_DOCUMENTO      <span class="text-muted">- Tag: &ltCODIGO_DOCUMENTO&gt</span></li>
                    <li>TITULO_DOCUMENTO      <span class="text-muted">- Tag: &ltTITULO_DOCUMENTO&gt</span></li>
                    <li>TIPO_DOCUMENTO        <span class="text-muted">- Tag: &ltTIPO_DOCUMENTO&gt</span></li>
                    <li>SETOR                 <span class="text-muted">- Tag: &ltSETOR&gt</span></li>
                </ul>
            </div>
        </div>
        <div class="col-md-6">
           <div class="form-group required{{ $errors->has('nome') ? ' has-error' : '' }}">
                {!! Form::label('nome', 'Nome', ['class' => 'control-label']) !!}
                {!! Form::text('nome', $nome, ['class' => 'form-control', 'required' => 'required']) !!}
                <small class="text-danger">{{ $errors->first('nome') }}</small>
           </div>
        </div>
        <div class="col-md-6">
            <div class="form-group required{{ $errors->has('tipoNotificacao') ? ' has-error' : '' }}">
            {!! Form::label('tipoNotificacao', 'Tipo de Notificação', ['class' => 'control-label']) !!}
            {!! Form::select('tipoNotificacao',$tiposNotificacao, !empty($notificacaoEdit) ?  $notificacaoEdit->tipo_id : null, ['id' => 'tipoNotificacao', 'class' => 'form-control selectpicker', 'required' => 'required', 'placeholder' => __('components.selectepicker-default')]) !!}
            <small class="text-danger">{{ $errors->first('tipoNotificacao') }}</small>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group required{{ $errors->has('tipoEnvio') ? ' has-error' : '' }}">
                {!! Form::label('tipoEnvio', 'Tipo Envio da Notificação', ['class' => 'control-label']) !!}
                {!! Form::select('tipoEnvio',$tiposEnvio, !empty($notificacaoEdit) ?  $notificacaoEdit->tipo_envio_notificacao_id : null, ['id' => 'tipoEnvio', 'required' => 'required', 'class' => 'form-control selectpicker', 'placeholder' => __('components.selectepicker-default')]) !!}
                <small class="text-danger">{{ $errors->first('tipoEnvio') }}</small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <div class="checkbox required{{ $errors->has('enviarAnexo') ? ' has-error' : '' }}">
                    {!! Form::label('enviarAnexo', 'Enviar Anexo', ['class' => 'control-label']) !!}
                    <td class="text-center text-nowrap">
                        <div class="switch">
                            <label for="enviarAnexo">Não
                                {!! Form::checkbox('enviarAnexo', '1', !empty($notificacaoEdit) ?  $notificacaoEdit->documento_anexo : false, ['id' => 'enviarAnexo', 'class'=> 'switch-elaborador']) !!}
                                <span class="lever switch-col-light-blue"></span>Sim
                            </label>
                        </div>
                    </td>    
                </div>
                <small class="text-danger">{{ $errors->first('copiaControlada') }}</small>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group required{{ $errors->has('titulo') ? ' has-error' : '' }}">
                {!! Form::label('titulo', 'Título', ['class' => 'control-label']) !!}
                {!! Form::textarea('titulo',$titulo, ['class' => 'form-control', 'required' => 'required','rows'=> 5]) !!}
                <small class="text-danger">{{ $errors->first('titulo') }}</small>
            </div> 
        </div>
        <div class="col-md-3">
            <div class="form-group{{ $errors->has('delay') ? ' has-error' : '' }}">
                {!! Form::label('delay', 'Delay Entre Envios (Em segundos)') !!}
                {!! Form::number('delay',$delay, ['class' => 'form-control']) !!}
                <small class="text-danger">{{ $errors->first('delay') }}</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group{{ $errors->has('tentativas') ? ' has-error' : '' }}">
                {!! Form::label('tentativas', 'N° de Tentativas') !!}
                {!! Form::number('tentativas',$tentativasEnvio, ['class' => 'form-control']) !!}
                <small class="text-danger">{{ $errors->first('tentativas') }}</small>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group required{{ $errors->has('corpo') ? ' has-error' : '' }}">
                {!! Form::label('corpo', 'Corpo', ['class' => 'control-label']) !!}
                {!! Form::textarea('corpo',$corpo, ['class' => 'form-control', 'required' => 'required', 'rows'=> 5]) !!}
                <small class="text-danger">{{ $errors->first('corpo') }}</small>
            </div>
        </div>
    </div>
</div>