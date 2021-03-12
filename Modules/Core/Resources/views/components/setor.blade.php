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
                {!! Form::text('descricao',$descricao, ['class' => 'form-control', 'required' => 'required']) !!}
                <small class="text-danger">{{ $errors->first('descricao') }}</small>
            </div>  
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group required{{ $errors->has('sigla') ? ' has-error' : '' }}">
                {!! Form::label('sigla', 'Sigla', ['class' => 'control-label']) !!}
                {!! Form::text('sigla',$sigla, ['class' => 'form-control', 'required' => 'required', 'disabled' => $achouDocumentos]) !!}
                <small class="text-danger">{{ $errors->first('sigla') }}</small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <div class="checkbox required{{ $errors->has('inativo') ? ' has-error' : '' }}">
                    {!! Form::label('inativo', 'Ativo', ['class' => 'control-label']) !!}
                    <td class="text-center text-nowrap">
                        <div class="switch">
                            <label for="inativo">Não
                                {!! Form::checkbox('inativo', '0', !empty($setorEdit) ?  ($setorEdit->inativo == 1 ? false : true) : true, ['id' => 'inativo', 'class'=> 'switch-elaborador']) !!}
                                <span class="lever switch-col-light-blue"></span>Sim
                            </label>
                        </div>
                    </td>    
                </div>
                <small class="text-danger">{{ $errors->first('inativo') }}</small>
            </div>
        </div>
    </div>
</div>