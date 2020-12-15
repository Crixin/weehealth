<div class="form-body">
    <div class="row p-t-20">
        <div class="col-md-6">
            <div class="form-group{{ $errors->has('codigo') ? ' has-error' : '' }}">
                {!! Form::label('codigo', 'Código') !!}
                {!! Form::text('codigo', $codigo, ['class' => 'form-control', 'disabled' => true]) !!}
                <small class="text-danger">{{ $errors->first('codigo') }}</small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group{{ $errors->has('validade') ? ' has-error' : '' }}">
                {!! Form::label('validade', 'Validade') !!}
                {!! Form::date('validade',$validade, ['class' => 'form-control', 'disabled'=>true]) !!}
                <small class="text-danger">{{ $errors->first('validade') }}</small>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group{{ $errors->has('tituloDocumento') ? ' has-error' : '' }}">
            {!! Form::label('tituloDocumento', 'Título do Documento') !!}
            {!! Form::text('tituloDocumento', $tituloDocumento, ['class' => 'form-control', 'required' => 'required']) !!}
            <small class="text-danger">{{ $errors->first('tituloDocumento') }}</small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group required{{ $errors->has('setor') ? ' has-error' : '' }}">
                {!! Form::label('setor', 'Setor', ['class' => 'control-label']) !!}
                {!! Form::select('setor',$setores, !empty($documentoEdit) ?  $documentoEdit->setor_id : null, ['id' => 'setor', 'class' => 'form-control selectpicker', 'required' => 'required', 'placeholder' => __('components.selectepicker-default')]) !!}
                <small class="text-danger">{{ $errors->first('setor') }}</small>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group required{{ $errors->has('tipoDocumento') ? ' has-error' : '' }}">
                {!! Form::label('tipoDocumento', 'Tipo de Documento' , ['class' => 'control-label']) !!}
                {!! Form::select('tipoDocumento',$tiposDocumento, !empty($documentoEdit) ?  $documentoEdit->tipo_documento_id : null, ['id' => 'tipoDocumento', 'class' => 'form-control selectpicker', 'required' => 'required', 'placeholder' => __('components.selectepicker-default')]) !!}
                <small class="text-danger">{{ $errors->first('tipoDocumento') }}</small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group{{ $errors->has('documentoPai') ? ' has-error' : '' }}">
                {!! Form::label('documentoPai', 'Documento(s) Pai') !!}
                {!! Form::select('documentoPai',$documentosPais, null, ['id' => 'documentoPai', 'class' => 'form-control selectpicker', 'multiple']) !!}
                <small class="text-danger">{{ $errors->first('documentoPai') }}</small>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group required{{ $errors->has('nivelAcesso') ? ' has-error' : '' }}">
                {!! Form::label('nivelAcesso', 'Nível de Acesso', ['class' => 'control-label']) !!}
                {!! Form::select('nivelAcesso',$niveisAcesso, !empty($documentoEdit) ?  $documentoEdit->nivel_acesso_id : null, ['id' => 'nivelAcesso', 'class' => 'form-control selectpicker', 'required' => 'required', 'placeholder' => __('components.selectepicker-default')]) !!}
                <small class="text-danger">{{ $errors->first('nivelAcesso') }}</small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group required{{ $errors->has('classificacao') ? ' has-error' : '' }}">
                {!! Form::label('classificacao', 'Classificação', ['class' => 'control-label']) !!}
                {!! Form::select('classificacao',$classificacoes, !empty($documentoEdit) ?  $documentoEdit->classificacao_id : null, ['id' => 'classificacao', 'class' => 'form-control selectpicker', 'required' => 'required', 'placeholder' => __('components.selectepicker-default')]) !!}
                <small class="text-danger">{{ $errors->first('classificacao') }}</small>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group{{ $errors->has('documentoVinculado') ? ' has-error' : '' }}">
                {!! Form::label('documentoVinculado', 'Documento(s) Vinculado(s)') !!}
                {!! Form::select('documentoVinculado',$documentosVinvulados, !empty($documentoEdit) ?  $documentoEdit->documento_vinculado : null, ['id' => 'documentoVinculado', 'class' => 'form-control selectpicker', 'multiple']) !!}
                <small class="text-danger">{{ $errors->first('documentoVinculado') }}</small>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <div class="checkbox required{{ $errors->has('copiaControlada') ? ' has-error' : '' }}">
                    {!! Form::label('copiaControlada', 'Copia Controlada', ['class' => 'control-label']) !!}
                    <td class="text-center text-nowrap">
                        <div class="switch">
                            <label for="copiaControlada">Não
                                {!! Form::checkbox('copiaControlada', '1', !empty($normaEdit) ?  $normaEdit->copia_controlada : true, ['id' => 'copiaControlada', 'class'=> 'switch-elaborador']) !!}
                                <span class="lever switch-col-light-blue"></span>Sim
                            </label>
                        </div>
                    </td>    
                </div>
                <small class="text-danger">{{ $errors->first('copiaControlada') }}</small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <div class="checkbox required{{ $errors->has('obsoleto') ? ' has-error' : '' }}">
                    {!! Form::label('obsoleto', 'Obsoleto', ['class' => 'control-label']) !!}
                    <td class="text-center text-nowrap">
                        <div class="switch">
                            <label for="obsoleto">Não
                                {!! Form::checkbox('obsoleto', '1', !empty($normaEdit) ?  $normaEdit->obsoleto : false, ['id' => 'obsoleto', 'class'=> 'switch-elaborador']) !!}
                                <span class="lever switch-col-light-blue"></span>Sim
                            </label>
                        </div>
                    </td>    
                </div>
                <small class="text-danger">{{ $errors->first('obsoleto') }}</small>
            </div>
        </div>
    </div>
    <legend>Grupos</legend>
    <hr>
    <div class="row">
        
    </div>
    
    <legend>Normas</legend>
    <hr>
    <div class="row">

    </div>

    <legend>Aprovadores</legend>
    <hr>
    <div class="row">
        
    </div>
</div>
