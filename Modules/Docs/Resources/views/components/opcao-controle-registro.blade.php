<div class="form-body">
    <div class="row p-t-20">
        <div class="col-md-6">
            <div class="form-group required{{ $errors->has('tipoControle') ? ' has-error' : '' }}">
                {!! Form::label('tipoControle', 'Tipo Controle de Registro', ['class' => 'control-label']) !!}
            
                {!! Form::select('tipoControle', $tipos, !empty($opcaoControleEdit) ?  $opcaoControleEdit->campo_id : null, ['id' => 'tipoControle', 'class' => 'form-control selectpicker ', 'required' => true, 'placeholder' => __('components.selectepicker-default') ]) !!}
                <small class="text-danger">{{ $errors->first('tipoControle') }}</small>
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
            <div class="form-group">
                <div class="checkbox required{{ $errors->has('ativo') ? ' has-error' : '' }}">
                    {!! Form::label('ativo', 'Ativo', ['class' => 'control-label']) !!}
                    <td class="text-center text-nowrap">
                        <div class="switch">
                            <label for="ativo">Não
                            {!! Form::checkbox('ativo', '1', !empty($opcaoControleEdit) ?  $opcaoControleEdit->ativo : true, ['id' => 'ativo', 'class'=> 'switch-elaborador']) !!}
                            <span class="lever switch-col-light-blue"></span>Sim
                            </label>
                        </div>
                    </td>    
                </div>
                <small class="text-danger">{{ $errors->first('ativo') }}</small>
            </div>
        </div>
    </div>
</div>