<div class="form-body">
    <div class="row p-t-20">
        <div class="col-md-6">
            <div class="form-group required{{ $errors->has('numero') ? ' has-error' : '' }}">
            {!! Form::label('numero', 'Número' , ['class' => 'control-label']) !!}
            {!! Form::text('numero',$numero, ['class' => 'form-control', 'required' => 'required']) !!}
            <small class="text-danger">{{ $errors->first('numero') }}</small>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">

            <div class="form-group required{{ $errors->has('descricao') ? ' has-error' : '' }}">
            {!! Form::label('descricao', 'Descrição', ['class' => 'control-label']) !!}
            {!! Form::textarea('descricao',$descricao, ['class' => 'form-control', 'rows'=>'5', 'required' => 'required']) !!}
            <small class="text-danger">{{ $errors->first('descricao') }}</small>
            </div>  
            


        </div>
    </div>
</div>