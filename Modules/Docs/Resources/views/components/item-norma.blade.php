<div class="form-body">
    <div class="row p-t-20">
        <div class="col-md-4">
            <div class="form-group required{{ $errors->has('numero') ? ' has-error' : '' }}">
            {!! Form::label('numero', 'Número' , ['class' => 'control-label']) !!}
            {!! Form::text('numero',$numero, ['class' => 'form-control', 'required' => 'required']) !!}
            <small class="text-danger">{{ $errors->first('numero') }}</small>
            </div>
        </div>
        <div class="col-md-8">
            <div class="form-group required{{ $errors->has('descricao') ? ' has-error' : '' }}">
                {!! Form::label('descricao', 'Descrição', ['class' => 'control-label']) !!}
                {!! Form::text('descricao', $descricao, ['class' => 'form-control', 'required' => 'required']) !!}
                <small class="text-danger">{{ $errors->first('descricao') }}</small>
            </div>
        </div>
    </div>
    
</div>