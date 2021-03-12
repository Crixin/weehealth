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
                <div class="checkbox required{{ $errors->has('status') ? ' has-error' : '' }}">
                    {!! Form::label('status', 'Ativo', ['class' => 'control-label']) !!}
                    <td class="text-center text-nowrap">
                        <div class="switch">
                            <label for="status">Não
                            {!! Form::checkbox('status', '1', !empty($planoEdit) ?  $planoEdit->ativo : true, ['id' => 'status', 'class'=> 'switch-elaborador']) !!}
                            <span class="lever switch-col-light-blue"></span>Sim
                            </label>
                        </div>
                    </td>    
                </div>
                <small class="text-danger">{{ $errors->first('status') }}</small>
            </div>
        </div>
    </div>
</div>