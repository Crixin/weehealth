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
            <div class="form-group required{{ $errors->has('sigla') ? ' has-error' : '' }}">
                {!! Form::label('sigla', 'Sigla', ['class' => 'control-label']) !!}
                {!! Form::text('sigla', $sigla, ['class' => 'form-control', 'required' => 'required']) !!}
                <small class="text-danger">{{ $errors->first('sigla') }}</small>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group required{{ $errors->has('descricao') ? ' has-error' : '' }}">
                {!! Form::label('descricao', 'Descrição', ['class' => 'control-label']) !!}
                {!! Form::text('descricao', $descricao, ['class' => 'form-control', 'required' => 'required']) !!}
                <small class="text-danger">{{ $errors->first('descricao') }}</small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group required{{ $errors->has('codigoPadrao') ? ' has-error' : '' }}">
                {!! Form::label('codigoPadrao', 'Padrão de Código', ['class' => 'control-label']) !!}
                
                {!! Form::select('codigoPadrao[]', $padroesCodigo, !empty($tipoDocumentoEdit) ?  json_decode($tipoDocumentoEdit->codigo_padrao) : null, ['id' => 'codigoPadrao', 'class' => 'form-control selectpicker', 'required' => 'required', 'multiple']) !!}
                <small class="text-danger">{{ $errors->first('codigoPadrao') }}</small>
            </div>
        </div>
        
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group required{{ $errors->has('periodoVigencia') ? ' has-error' : '' }}">
                {!! Form::label('periodoVigencia', 'Período de Vigência', ['class' => 'control-label']) !!}
            
                {!! Form::select('periodoVigencia', $periodosVigencia, !empty($tipoDocumentoEdit) ?  $tipoDocumentoEdit->periodo_vigencia_id : null, ['id' => 'periodoVigencia', 'class' => 'form-control selectpicker ', 'required' => true, 'placeholder' => __('components.selectepicker-default') ]) !!}
                <small class="text-danger">{{ $errors->first('periodoVigencia') }}</small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group required{{ $errors->has('periodoAviso') ? ' has-error' : '' }}">
                {!! Form::label('periodoAviso', 'Período para Aviso de Vencimento', ['class' => 'control-label']) !!}
                {!! Form::select('periodoAviso', $periodosAviso, !empty($tipoDocumentoEdit) ?  $tipoDocumentoEdit->periodo_aviso_id : null, ['id' => 'periodoAviso', 'class' => 'form-control selectpicker ', 'required' => true, 'placeholder' => __('components.selectepicker-default') ]) !!}
                <small class="text-danger">{{ $errors->first('periodoAviso') }}</small>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group{{ $errors->has('tipoDocumentoPai') ? ' has-error' : '' }}">
                {!! Form::label('tipoDocumentoPai', 'Tipo Documento Pai') !!}
                {!! Form::select('tipoDocumentoPai',$tiposDocumento, !empty($tipoDocumentoEdit) ?  $tipoDocumentoEdit->tipo_documento_pai_id : null, ['id' => 'tipoDocumentoPai', 'class' => 'form-control selectpicker', 'placeholder' => __('components.selectepicker-default')]) !!}
                <small class="text-danger">{{ $errors->first('tipoDocumentoPai') }}</small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group required{{ $errors->has('fluxo') ? ' has-error' : '' }}">
            {!! Form::label('fluxo', 'Fluxo' , ['class' => 'control-label']) !!}
            {!! Form::select('fluxo',$fluxos, !empty($tipoDocumentoEdit) ?  $tipoDocumentoEdit->fluxo_id : null, ['id' => 'fluxo', 'class' => 'form-control selectpicker', 'required' => 'required', 'placeholder' => __('components.selectepicker-default')]) !!}
            <small class="text-danger">{{ $errors->first('fluxo') }}</small>
            </div>
        </div>
    </div>
    <div class="row">    
        <div class="col-md-6">
            <div class="form-group required{{ $errors->has('documentoModelo') ? ' has-error' : '' }}">
                {!! Form::label('documentoModelo', 'Modelo de Documento', ['class' => 'control-label']) !!}
                {!! Form::file('documentoModelo', [empty($tipoDocumentoEdit) ? 'required': '', 'accept' =>'.doc, .xls, .DOC, .XLS']) !!}
                <small class="text-danger">{{ $errors->first('documentoModelo') }}</small>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            
            <div class="form-group">
                <div class="checkbox{{ $errors->has('ativo') ? ' has-error' : '' }}">
                    <label class="control-label">Status</label>
                    <td class="text-center text-nowrap">
                        <div class="switch">
                            <label for="ativo">Inativo
                            {!! Form::checkbox('ativo', '1', !empty($tipoDocumentoEdit) ?  $tipoDocumentoEdit->ativo : true, ['id' => 'ativo', 'class'=> 'switch-elaborador']) !!}
                            <span class="lever switch-col-light-blue"></span>Ativo
                            </label>
                        </div>
                    </td>    
                </div>
                <small class="text-danger">{{ $errors->first('ativo') }}</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <div class="checkbox{{ $errors->has('vinculoObrigatorio') ? ' has-error' : '' }}">
                    <label class="control-label">Vínculo Obrigatório</label>
                                    
                    <td class="text-center text-nowrap">
                        <div class="switch">
                            <label>Não
                                {!! Form::checkbox('vinculoObrigatorio', '1', !empty($tipoDocumentoEdit) ?  $tipoDocumentoEdit->vinculo_obrigatorio : true, ['id' => 'vinculoObrigatorio', 'class'=> 'switch-elaborador']) !!}<span class="lever switch-col-light-blue"></span>Sim
                            </label>
                        </div>
                    </td>
                <small class="text-danger">{{ $errors->first('vinculoObrigatorio') }}</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <div class="checkbox{{ $errors->has('permitirDownload') ? ' has-error' : '' }}">
                    <label class="control-label">Permitir Download</label>
                                    
                    <td class="text-center text-nowrap">
                        <div class="switch">
                            <label>Não
                                {!! Form::checkbox('permitirDownload', '1', !empty($tipoDocumentoEdit) ?  $tipoDocumentoEdit->permitir_download : true, ['id' => 'permitirDownload', 'class'=> 'switch-elaborador']) !!}<span class="lever switch-col-light-blue"></span>Sim
                            </label>
                        </div>
                    </td>
                <small class="text-danger">{{ $errors->first('permitirDownload') }}</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <div class="checkbox{{ $errors->has('permitirImpressao') ? ' has-error' : '' }}">
                    <label class="control-label">Permitir Impressão</label>
                                    
                    <td class="text-center text-nowrap">
                        <div class="switch">
                            <label>Não
                                {!! Form::checkbox('permitirImpressao', '1', !empty($tipoDocumentoEdit) ?  $tipoDocumentoEdit->permitir_impressao : true, ['id' => 'permitirImpressao', 'class'=> 'switch-elaborador']) !!}<span class="lever switch-col-light-blue"></span>Sim
                            </label>
                        </div>
                    </td>
                <small class="text-danger">{{ $errors->first('permitirImpressao') }}</small>
                </div>
            </div>
        </div>        
    </div>
    <div class="row">

    </div>
    
</div>