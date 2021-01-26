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
            <div class="form-group required{{ $errors->has('descricao') ? ' has-error' : '' }}">
                {!! Form::label('descricao', 'Descrição', ['class' => 'control-label']) !!}
                {!! Form::text('descricao', $descricao, ['class' => 'form-control', 'required' => 'required']) !!}
                <small class="text-danger">{{ $errors->first('descricao') }}</small>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group {{ $errors->has('versao') ? ' has-error' : '' }}">
                {!! Form::label('versao', 'Versão') !!}
                {!! Form::text('versao', $versao, ['class' => 'form-control', 'readonly' => true]) !!}
                
                <small class="text-danger">{{ $errors->first('versao') }}</small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <div class="checkbox required{{ $errors->has('ativo') ? ' has-error' : '' }}">
                    {!! Form::label('status', 'Status', ['class' => 'control-label']) !!}
                    <td class="text-center text-nowrap">
                        <div class="switch">
                            <label for="ativo">Inativo
                            {!! Form::checkbox('ativo', '1', !empty($fluxoEdit) ?  $fluxoEdit->ativo : true, ['id' => 'ativo', 'class'=> 'switch-elaborador']) !!}
                            <span class="lever switch-col-light-blue"></span>Ativo
                            </label>
                        </div>
                    </td>    
                </div>
                <small class="text-danger">{{ $errors->first('ativo') }}</small>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group required{{ $errors->has('grupo') ? ' has-error' : '' }}">
                {!! Form::label('grupo', 'Grupo', ['class' => 'control-label']) !!}
            
                {!! Form::select('grupo', $grupos, !empty($fluxoEdit) ?  $fluxoEdit->grupo_id : null, ['id' => 'grupo', 'class' => 'form-control selectpicker ', 'required' => true, 'placeholder' => __('components.selectepicker-default') ]) !!}
                <small class="text-danger">{{ $errors->first('grupo') }}</small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group required{{ $errors->has('perfil') ? ' has-error' : '' }}">
                {!! Form::label('perfil', 'Perfil', ['class' => 'control-label']) !!}
            
                {!! Form::select('perfil', $perfis, !empty($fluxoEdit) ?  $fluxoEdit->perfil_id : null, ['id' => 'perfil', 'class' => 'form-control selectpicker ', 'required' => true, 'placeholder' => __('components.selectepicker-default') ]) !!}
                <small class="text-danger">{{ $errors->first('perfil') }}</small>
            </div>
        </div>
    </div>
</div>