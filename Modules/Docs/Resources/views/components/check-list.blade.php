<div class="form-body">
    <div class="row p-t-20">
        <div class="col-md-12">
            <div class="form-group required{{ $errors->has('descricao') ? ' has-error' : '' }}">
                {!! Form::label('descricao', 'Descrição' , ['class' => 'control-label']) !!}
                {!! Form::textarea('descricao', $descricao, ['class' => 'form-control', 'required' => 'required', 'rows' =>'5']) !!}
                <small class="text-danger">{{ $errors->first('descricao') }}</small>
            </div>
        </div>
    </div>
</div>