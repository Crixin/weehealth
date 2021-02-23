<div class="row mt-3">
    <div class="col-md-12">
        <form method="POST" action="{{route('docs.agrupamento-user-documento.confirmar-leitura')}}">
            {{ csrf_field() }} 
            {{ Form::hidden('documento_id', $documento) }}
            <div class="form-group">
                <div class="checkbox{{ $errors->has('lido') ? ' has-error' : '' }}">
                    <input type="checkbox" @if ($lido){ checked disabled }@endif  name="lido" id="lido" />
                    <label for="lido">Eu defino esse(s) documento(s) como <span class="font-weight-bold">lido(s)</span>.</label>
                </div>
                <small class="text-danger">{{ $errors->first('lido') }}</small>
            </div>
            @if (!$lido)
                {!! Form::submit("Confirmar leitura " , ['class' => 'btn btn-lg btn-success']) !!}     
            @endif
        </form>
    </div>
</div>