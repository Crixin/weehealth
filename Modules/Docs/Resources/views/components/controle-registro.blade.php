<div class="form-body">
    <div class="row p-t-20">
        <div class="col-md-6">
            <div class="form-group required{{ $errors->has('codigo') ? ' has-error' : '' }}">
                {!! Form::label('codigo', 'Código', ['class' => 'control-label']) !!}
                {!! Form::text('codigo', $codigo, ['class' => 'form-control', 'required' => 'required']) !!}
                <small class="text-danger">{{ $errors->first('codigo') }}</small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group required{{ $errors->has('descricao') ? ' has-error' : '' }}">
                {!! Form::label('descricao', 'Título/Descrição', ['class' => 'control-label']) !!}
                {!! Form::text('descricao', $descricao, ['class' => 'form-control', 'required' => 'required']) !!}
                <small class="text-danger">{{ $errors->first('descricao') }}</small>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group required{{ $errors->has('responsavel') ? ' has-error' : '' }}">
                {!! Form::label('responsavel', 'Responsável', ['class' => 'control-label']) !!}
                {!! Form::select('responsavel', $responsaveis, null, ['id' => 'responsavel', 'class' => 'form-control', 'required' => 'required']) !!}
                <small class="text-danger">{{ $errors->first('responsavel') }}</small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group required{{ $errors->has('meio') ? ' has-error' : '' }}">
                {!! Form::label('meio', 'Meio', ['class' => 'control-label']) !!}
                {!! Form::select('meio', $meios, null, ['id' => 'meio', 'class' => 'form-control', 'required' => 'required']) !!}
                <small class="text-danger">{{ $errors->first('meio') }}</small>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group required{{ $errors->has('armazenamento') ? ' has-error' : '' }}">
                {!! Form::label('armazenamento', 'Armazenamento', ['class' => 'control-label']) !!}
                {!! Form::select('armazenamento', $meiosArmazenamento, null, ['id' => 'armazenamento', 'class' => 'form-control', 'required' => 'required']) !!}
                <small class="text-danger">{{ $errors->first('armazenamento') }}</small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group{{ $errors->has('protecao') ? ' has-error' : '' }}">
                {!! Form::label('protecao', 'Proteção') !!}
                {!! Form::select('protecao', $meiosProtecao, null, ['id' => 'protecao', 'class' => 'form-control', 'required' => 'required']) !!}
                <small class="text-danger">{{ $errors->first('protecao') }}</small>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group{{ $errors->has('recuperacao') ? ' has-error' : '' }}">
                {!! Form::label('recuperacao', 'Recuperação') !!}
                {!! Form::select('recuperacao',$meiosRecuperacao, null, ['id' => 'recuperacao', 'class' => 'form-control', 'required' => 'required']) !!}
                <small class="text-danger">{{ $errors->first('recuperacao') }}</small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group{{ $errors->has('nivelAcesso') ? ' has-error' : '' }}">
                {!! Form::label('nivelAcesso', 'Nível Acesso') !!}
                {!! Form::select('nivelAcesso', $niveisAcesso, null, ['id' => 'nivelAcesso', 'class' => 'form-control', 'required' => 'required']) !!}
                <small class="text-danger">{{ $errors->first('nivelAcesso') }}</small>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group{{ $errors->has('retencaoLocal') ? ' has-error' : '' }}">
                {!! Form::label('retencaoLocal', 'Retenção Mínima-Local') !!}
                {!! Form::select('retencaoLocal', $opcoesRetencaoLocal, null, ['id' => 'retencaoLocal', 'class' => 'form-control', 'required' => 'required']) !!}
                <small class="text-danger">{{ $errors->first('retencaoLocal') }}</small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group{{ $errors->has('retencaoDeposito') ? ' has-error' : '' }}">
                {!! Form::label('retencaoDeposito', 'Retenção Mínima-Arquivo Morto') !!}
                {!! Form::select('retencaoDeposito', $opcoesRetencaoDeposito, null, ['id' => 'retencaoDeposito', 'class' => 'form-control', 'required' => 'required']) !!}
                <small class="text-danger">{{ $errors->first('retencaoDeposito') }}</small>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group{{ $errors->has('disposicao') ? ' has-error' : '' }}">
                {!! Form::label('disposicao', 'Input label') !!}
                {!! Form::select('disposicao', $options, null, ['id' => 'disposicao', 'class' => 'form-control', 'required' => 'required', 'multiple']) !!}
                <small class="text-danger">{{ $errors->first('disposicao') }}</small>
            </div>
        </div>
    </div>
</div>