<div class="form-body">
    <div class="row p-t-20">
        <div class="col-md-6">
            <div class="form-group{{ $errors->has('tituloDocumento') ? ' has-error' : '' }}">
            {!! Form::label('tituloDocumento', 'Título do Documento') !!}
            {!! Form::text('tituloDocumento', $tituloDocumento, ['class' => 'form-control', 'required' => 'required']) !!}
            <small class="text-danger">{{ $errors->first('tituloDocumento') }}</small>
            </div>
        </div>
    </div>
</div>