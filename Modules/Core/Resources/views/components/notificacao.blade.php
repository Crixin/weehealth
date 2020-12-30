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
            <div class="form-group required{{ $errors->has('tipoEnvio') ? ' has-error' : '' }}">
                {!! Form::label('tipoEnvio', 'Tipo Envio da Notificação', ['class' => 'control-label']) !!}
                {!! Form::select('tipoEnvio',$tiposEnvio, !empty($notificacaoEdit) ?  $notificacaoEdit->tipo_envio_notificacao_id : null, ['id' => 'tipoEnvio', 'required' => 'required', 'class' => 'form-control selectpicker', 'placeholder' => __('components.selectepicker-default')]) !!}
                <small class="text-danger">{{ $errors->first('tipoEnvio') }}</small>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group required{{ $errors->has('titulo') ? ' has-error' : '' }}">
            {!! Form::label('titulo', 'Título', ['class' => 'control-label']) !!}
            {!! Form::text('titulo',$titulo, ['class' => 'form-control', 'required' => 'required']) !!}
            <small class="text-danger">{{ $errors->first('titulo') }}</small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="alert alert-info">
                <span><b>Exemplos de Tags disponíveis:</b></span>
                <ul>
                    <li>DATA_ELABORACAO       <span class="text-muted">- Tag utilizada &ltDATA_ELABORACAO&gt</span></li>
                    <li>ELABORADOR            <span class="text-muted">- Tag utilizada &ltELABORADOR&gt</span></li>
                    <li>APROVADOR             <span class="text-muted">- Tag utilizada &ltAPROVADOR&gt</span></li>
                    <li>DATA_REVISAO          <span class="text-muted">- Tag utilizada &ltDATA_REVISAO&gt</span></li>
                    <li>VERSAO                <span class="text-muted">- Tag utilizada &ltVERSAO&gt</span></li>
                    <li>CODIGO_DOCUMENTO      <span class="text-muted">- Tag utilizada &ltCODIGO_DOCUMENTO&gt</span></li>
                    <li>TITULO_DOCUMENTO      <span class="text-muted">- Tag utilizada &ltTITULO_DOCUMENTO&gt</span></li>
                    <li>COLABORADORES         <span class="text-muted">- Tag utilizada &ltCOLABORADORES&gt</span></li>
                    <li>VERIFICADOR_QUALIDADE <span class="text-muted">- Tag utilizada &ltVERIFICADOR_QUALIDADE&gt</span></li>
                    <li>TIPO_DOCUMENTO        <span class="text-muted">- Tag utilizada &ltTIPO_DOCUMENTO&gt</span></li>
                    <li>SETOR                 <span class="text-muted">- Tag utilizada &ltSETOR&gt</span></li>
                </ul>
                <!--<small><b>Lembre-se:</b> são aceitos apenas 4 dígitos!</small>-->
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