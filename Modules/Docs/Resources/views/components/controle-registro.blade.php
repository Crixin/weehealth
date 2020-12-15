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
                {!! Form::select('responsavel', $responsaveis, !empty($controleRegistroEdit) ?  $controleRegistroEdit->setor_id : null, ['id' => 'responsavel', 'class' => 'form-control', 'required' => 'required','placeholder' => __('components.selectepicker-default')]) !!}
                <small class="text-danger">{{ $errors->first('responsavel') }}</small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group required{{ $errors->has('meio') ? ' has-error' : '' }}">
                {!! Form::label('meio', 'Meio', ['class' => 'control-label']) !!}
                {!! Form::select('meio', $meios, !empty($controleRegistroEdit) ?  $controleRegistroEdit->meio_distribuicao_id : null, ['id' => 'meio', 'class' => 'form-control', 'required' => 'required','placeholder' => __('components.selectepicker-default')]) !!}
                <small class="text-danger">{{ $errors->first('meio') }}</small>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group required{{ $errors->has('armazenamento') ? ' has-error' : '' }}">
                {!! Form::label('armazenamento', 'Armazenamento', ['class' => 'control-label']) !!}
                {!! Form::select('armazenamento', $meiosArmazenamento, !empty($controleRegistroEdit) ?  $controleRegistroEdit->local_armazenamento_id : null, ['id' => 'armazenamento', 'class' => 'form-control', 'required' => 'required','placeholder' => __('components.selectepicker-default')]) !!}
                <small class="text-danger">{{ $errors->first('armazenamento') }}</small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group required{{ $errors->has('protecao') ? ' has-error' : '' }}">
                {!! Form::label('protecao', 'Proteção', ['class' => 'control-label']) !!}
                {!! Form::select('protecao', $meiosProtecao, !empty($controleRegistroEdit) ?  $controleRegistroEdit->protecao_id : null, ['id' => 'protecao', 'class' => 'form-control', 'required' => 'required','placeholder' => __('components.selectepicker-default')]) !!}
                <small class="text-danger">{{ $errors->first('protecao') }}</small>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group required{{ $errors->has('recuperacao') ? ' has-error' : '' }}">
                {!! Form::label('recuperacao', 'Recuperação', ['class' => 'control-label']) !!}
                {!! Form::select('recuperacao',$meiosRecuperacao, !empty($controleRegistroEdit) ?  $controleRegistroEdit->recuperacao_id : null, ['id' => 'recuperacao', 'class' => 'form-control', 'required' => 'required','placeholder' => __('components.selectepicker-default')]) !!}
                <small class="text-danger">{{ $errors->first('recuperacao') }}</small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group required{{ $errors->has('nivelAcesso') ? ' has-error' : '' }}">
                {!! Form::label('nivelAcesso', 'Nível Acesso', ['class' => 'control-label']) !!}
                {!! Form::select('nivelAcesso', $niveisAcesso, !empty($controleRegistroEdit) ?  $controleRegistroEdit->nivel_acesso : null, ['id' => 'nivelAcesso', 'class' => 'form-control', 'required' => 'required','placeholder' => __('components.selectepicker-default')]) !!}
                <small class="text-danger">{{ $errors->first('nivelAcesso') }}</small>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group required{{ $errors->has('retencaoLocal') ? ' has-error' : '' }}">
                {!! Form::label('retencaoLocal', 'Retenção Mínima-Local', ['class' => 'control-label']) !!}
                {!! Form::select('retencaoLocal', $opcoesRetencaoLocal, !empty($controleRegistroEdit) ?  $controleRegistroEdit->tempo_retencao_local_id : null, ['id' => 'retencaoLocal', 'class' => 'form-control', 'required' => 'required','placeholder' => __('components.selectepicker-default')]) !!}
                <small class="text-danger">{{ $errors->first('retencaoLocal') }}</small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group required{{ $errors->has('retencaoDeposito') ? ' has-error' : '' }}">
                {!! Form::label('retencaoDeposito', 'Retenção Mínima-Arquivo Morto', ['class' => 'control-label']) !!}
                {!! Form::select('retencaoDeposito', $opcoesRetencaoDeposito, !empty($controleRegistroEdit) ?  $controleRegistroEdit->tempo_retencao_deposito_id : null, ['id' => 'retencaoDeposito', 'class' => 'form-control', 'required' => 'required','placeholder' => __('components.selectepicker-default')]) !!}
                <small class="text-danger">{{ $errors->first('retencaoDeposito') }}</small>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group required{{ $errors->has('disposicao') ? ' has-error' : '' }}">
            {!! Form::label('disposicao', 'Disposição', ['class' => 'control-label']) !!}
            {!! Form::select('disposicao',$disposicoes, !empty($controleRegistroEdit) ?  $controleRegistroEdit->disposicao_id : null, ['id' => 'disposicao', 'class' => 'form-control', 'required' => 'required','placeholder' => __('components.selectepicker-default')]) !!}
            <small class="text-danger">{{ $errors->first('disposicao') }}</small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <div class="checkbox required{{ $errors->has('ativo') ? ' has-error' : '' }}">
                    {!! Form::label('ativo', 'Ativo', ['class' => 'control-label']) !!}
                    <td class="text-center text-nowrap">
                        <div class="switch">
                            <label for="ativo">Não
                            {!! Form::checkbox('ativo', '1', !empty($controleRegistroEdit) ?  $controleRegistroEdit->ativo : true, ['id' => 'ativo', 'class'=> 'switch-elaborador']) !!}
                            <span class="lever switch-col-light-blue"></span>Sim
                            </label>
                        </div>
                    </td>    
                </div>
                <small class="text-danger">{{ $errors->first('ativo') }}</small>
            </div>
        </div>
    </div>
</div>