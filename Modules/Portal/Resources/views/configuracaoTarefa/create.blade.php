@extends('layouts.app')

@extends('layouts.menuPortal')
@yield('menu')


@section('page_title', __('page_titles.portal.configuracaoTarefa.create'))


@section('breadcrumbs')

    <li class="breadcrumb-item"><a href="{{ route('portal.home') }}"> @lang('page_titles.general.home') </a></li>
    <li class="breadcrumb-item"><a href="{{ route('portal.config-tarefa') }}"> @lang('page_titles.portal.configuracaoTarefa.index') </a></li>
    <li class="breadcrumb-item active"> @lang('page_titles.portal.configuracaoTarefa.create') </li>    

@endsection

@section('content')

    <div class="col-12">
        <div class="card">
            <div class="card-body">


                @if(Session::has('message'))
                    @component('components.alert')
                    @endcomponent

                    {{ Session::forget('message') }}
                @endif
                
                <form method="POST" action="{{ route('portal.config-tarefa.salvar') }}">
                    {{ csrf_field() }}
                    <div class="form-body">
                        
                        <div class="row p-t-20">
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('nome') ? ' has-error' : '' }}">
                                    <label class="control-label">Nome</label>
                                    <input type="text" id="nome" name="nome" value="{{ old('nome') }}" class="form-control" required autofocus>
                                    <small class="form-control-feedback"> Digite o nome da configuração. </small> 

                                    @if ($errors->has('nome'))
                                        <br/>    
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('nome') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('tipoConfiguracao') ? ' has-error' : '' }}">
                                    <label class="control-label">Tipo de Configuração</label>
                                    <select class="form-control selectpicker" name="tipoConfiguracao" id="tipoConfiguracao" required>
                                        <option value="" >Selecione</option>
                                        <option value="FTP" {{old('tipoConfiguracao') == "FTP" ? 'selected' : ''}} >FTP</option>
                                        <option value="PASTA_SERVIDOR" {{old('tipoConfiguracao') == "PASTA_SERVIDOR" ? 'selected' : ''}} >Pasta do Sistema</option>
                                    </select>
                                    <small class="form-control-feedback"> Selecione o tipo de configuração. </small> 

                                    @if ($errors->has('tipoConfiguracao'))
                                        <br/>
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('tipoConfiguracao') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row" id="divPastaSistema" style="display: none">
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('pastaSistema') ? ' has-error' : '' }}">
                                    <label class="control-label">Caminho do Sistema (Ex: /opt/arquivos/ )</label>
                                    <input type="text" id="pastaSistema" name="pastaSistema" value="{{ old('pastaSistema') }}" class="form-control" >
                                    <small class="form-control-feedback"> Digite o caminho da pasta.</small> 

                                    @if ($errors->has('pastaSistema'))
                                        <br/>    
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('pastaSistema') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row" id="divFTP" style="display: none">
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('ip') ? ' has-error' : '' }}">
                                    <label class="control-label">IP</label>
                                    <input type="text" id="ip" name="ip" value="{{ old('ip') }}" class="form-control ip_address" >
                                    <small class="form-control-feedback"> Digite o ip. </small> 
                                    @if ($errors->has('ip'))
                                        <br/>    
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('ip') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('porta') ? ' has-error' : '' }}">
                                    <label class="control-label">Porta</label>
                                    <input type="number" min="1" id="porta" name="porta" value="{{ old('porta') }}" class="form-control" >
                                    <small class="form-control-feedback"> Digite a porta. </small> 
                                    @if ($errors->has('porta'))
                                        <br/>    
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('porta') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('usuario') ? ' has-error' : '' }}">
                                    <label class="control-label">Usuário</label>
                                    <input type="text" id="usuario" name="usuario" value="{{ old('usuario') }}" class="form-control " >
                                    <small class="form-control-feedback"> Digite o usuário. </small> 
                                    @if ($errors->has('usuario'))
                                        <br/>    
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('usuario') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('senha') ? ' has-error' : '' }}">
                                    <label class="control-label">Senha</label>
                                    <div class="input-group">
                                        <input type="password" id="senha" name="senha" value="{{ old('senha') }}" class="form-control " >
                                        <div class="input-group-append" id="visualizaSenha"  title="Mantenha pressionado para visualizar sua senha">
                                            <span class="input-group-text">
                                                <i class="ti-eye"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <small class="form-control-feedback"> Digite a senha. </small> 
                                    @if ($errors->has('senha'))
                                        <br/>    
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('senha') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> @lang('buttons.general.save')</button>
                        <a href="{{ route('portal.config-tarefa') }}" class="btn btn-inverse"> @lang('buttons.general.back')</a>
                    </div>
                </form>
            
            </div>
        </div>
    </div>
    
@endsection

@section('footer')
<script type="text/javascript" src="{{asset('js/controller/configuracaoTarefa.js')}}"></script>
@endsection 