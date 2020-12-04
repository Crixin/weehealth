<div class="form-body">
    <div class="row p-t-20">
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
            <div class="form-group required{{ $errors->has('orgaoRegulador') ? ' has-error' : '' }}">
                {!! Form::label('orgaoRegulador', 'Orgão Regulador', ['class' => 'control-label']) !!}
            
                {!! Form::select('orgaoRegulador', $orgaos, !empty($normaEdit) ?  $normaEdit->orgao_regulador_id : null, ['id' => 'orgaoRegulador', 'class' => 'form-control selectpicker ', 'required' => true, 'placeholder' => __('components.selectepicker-default') ]) !!}
                <small class="text-danger">{{ $errors->first('orgaoRegulador') }}</small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group required{{ $errors->has('cicloAuditoria') ? ' has-error' : '' }}">
                {!! Form::label('cicloAuditoria', 'Ciclo de Auditoria', ['class' => 'control-label']) !!}
            
                {!! Form::select('cicloAuditoria', $ciclos, !empty($normaEdit) ?  $normaEdit->ciclo_auditoria_id : null, ['id' => 'cicloAuditoria', 'class' => 'form-control selectpicker ', 'required' => true, 'placeholder' => __('components.selectepicker-default') ]) !!}
                <small class="text-danger">{{ $errors->first('cicloAuditoria') }}</small>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group required{{ $errors->has('dataAcreditacao') ? ' has-error' : '' }}">
            {!! Form::label('dataAcreditacao', 'Data Acreditação' , ['class' => 'control-label']) !!}
            {!! Form::date('dataAcreditacao', !empty($normaEdit) ?  $normaEdit->data_acreditacao : null, ['class' => 'form-control', 'required' => 'required']) !!}
            <small class="text-danger">{{ $errors->first('dataAcreditacao') }}</small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <div class="checkbox required{{ $errors->has('vigente') ? ' has-error' : '' }}">
                    {!! Form::label('status', 'Vigente', ['class' => 'control-label']) !!}
                    <td class="text-center text-nowrap">
                        <div class="switch">
                            <label for="vigente">Não
                            {!! Form::checkbox('vigente', '1', !empty($fluxoEdit) ?  $normaEdit->vigente : true, ['id' => 'vigente', 'class'=> 'switch-elaborador']) !!}
                            <span class="lever switch-col-light-blue"></span>Sim
                            </label>
                        </div>
                    </td>    
                </div>
                <small class="text-danger">{{ $errors->first('vigente') }}</small>
            </div>
        </div>
    </div>
</div>