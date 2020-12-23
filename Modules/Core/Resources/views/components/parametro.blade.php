<div class="form-body">
    <div class="row p-t-20">
        <div class="col-md-6">
           <div class="form-group {{ $errors->has('identificadorParametro') ? ' has-error' : '' }}">
                {!! Form::label('identificadorParametro', 'Identificador Parâmetro') !!}
                {!! Form::text('identificadorParametro', $parametroEdit->identificador_parametro, ['class' => 'form-control', 'disabled' =>'true', 'required' => 'required']) !!}
                <small class="text-danger">{{ $errors->first('identificadorParametro') }}</small>
           </div>
        </div>
        <div class="col-md-6">
            <div class="form-group required{{ $errors->has('descricao') ? ' has-error' : '' }}">
                {!! Form::label('descricao', 'Descrição' , ['class' => 'control-label']) !!}
                {!! Form::text('descricao',$parametroEdit->descricao, ['class' => 'form-control', 'required' => 'required']) !!}
                <small class="text-danger">{{ $errors->first('descricao') }}</small>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group required{{ $errors->has('valorPadrao') ? ' has-error' : '' }}">
            {!! Form::label('valorPadrao', 'Valor Padrão' , ['class' => 'control-label']) !!}
            {!! Form::textarea('valorPadrao',$parametroEdit->valor_padrao, ['class' => 'form-control', 'required' => 'required']) !!}
            <small class="text-danger">{{ $errors->first('valorPadrao') }}</small>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group required{{ $errors->has('valorUsuario') ? ' has-error' : '' }}">
            {!! Form::label('valorUsuario', 'Valor Usuário' , ['class' => 'control-label']) !!}
            {!! Form::text('valorUsuario',$parametroEdit->valor_usuario, ['class' => 'form-control', 'required' => 'required']) !!}
            <small class="text-danger">{{ $errors->first('valorUsuario') }}</small>
            </div>
        </div>
    </div>   
</div>