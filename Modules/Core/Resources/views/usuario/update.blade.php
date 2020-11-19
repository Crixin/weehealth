@extends('layouts.app')

@extends('layouts.menuCore')
@yield('menu')


@section('page_title', __('page_titles.user.update'))


@section('breadcrumbs')

    <li class="breadcrumb-item"><a href="{{ route('core.home') }}"> @lang('page_titles.general.home') </a></li>
    <li class="breadcrumb-item"><a href="{{ route('usuario') }}"> @lang('page_titles.user.index') </a></li>
    <li class="breadcrumb-item active"> @lang('page_titles.user.update') </li>    

@endsection



@section('content')

    <div class="col-12">
        <div class="card">
            <div class="card-body">
            
                @if(Session::has('message'))
                    @component('componentes.alert')
                    @endcomponent
                    
                    {{ Session::forget('message') }}
                @endif

                <form method="POST" action="{{ route('usuario.alterar') }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="idUsuario" value="{{ $usuario->id }}">

                    <div class="form-body">
                        <h3 class="box-title"> @lang('page_titles.user.person_info') </h3>
                        <hr class="m-t-0 m-b-10">

                        <div class="row p-t-20">
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                    <label class="control-label">Nome Completo</label>
                                    <input type="text" id="name" name="name" value="{{ $usuario->name }}" class="form-control" required autofocus>
                                    <small class="form-control-feedback"> Digite seu nome e sobrenome no campo acima. </small> 

                                    @if ($errors->has('name'))
                                        <br/>    
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                                    <label class="control-label">Nome de Usuário</label>
                                    <input type="text" id="username" class="form-control" name="username" value="{{ $usuario->username }}" required>
                                    <small class="form-control-feedback"> Esse valor poderá ser usado para realizar <i>login</i> no sistema. </small> 

                                    @if ($errors->has('username'))
                                        <br/>
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('username') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                    <label class="control-label">E-mail</label>
                                    <input type="email" id="email" name="email" value="{{ $usuario->email }}" class="form-control" required>

                                    @if ($errors->has('email'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('foto') ? ' has-error' : '' }}">
                                    <label class="control-label">Foto</label>
                                    <input type="file" id="foto" name="foto" class="form-control" >
                                    <small class="form-control-feedback"> São permitidos os formatos jpeg, png e jpg </small> 
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('perfil') ? ' has-error' : '' }}">
                                    <label class="control-label">Perfil</label>
                                    <select name="perfil" id="perfil" class="form-control text-center selectpicker" required data-size="10" data-live-search="true">
                                        @foreach ($perfis as $perfil)
                                            <option value="{{ $perfil->id }}" {{$perfil->id == ($usuario->perfil->id ?? "") ? "selected" : ""}}> {{ $perfil->nome }} </option>
                                        @endforeach
                                    </select>
                                    <small class="form-control-feedback"> Selecione o perfil. </small> 

                                    @if ($errors->has('perfil'))
                                        <br/>
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('perfil') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> @lang('buttons.general.save')</button>
                        <a href="{{ route('usuario') }}" class="btn btn-inverse"> @lang('buttons.general.back')</a>
                    </div>

                </form>

                    

                <form method="POST" action="{{ route('usuario.alterarSenha') }}">
                    {{ csrf_field() }}
                    <input type="hidden" name="idUsuario" value="{{ $usuario->id }}">

                    <div class="form-body">
                        <h3 class="m-t-40 box-title"> @lang('page_titles.user.password') </h3>
                        <hr class="m-t-5 m-b-10">

                        <div class="row p-t-20">
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                    <label class="control-label">Senha</label>
                                    <input type="password" id="password" class="form-control" name="password" required>

                                    @if ($errors->has('password'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                    <label class="control-label">Senha (confirmação)</label>
                                    <input type="password" id="password-confirm" class="form-control" name="password_confirmation" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> @lang('buttons.general.save')</button>
                        <a href="{{ route('usuario') }}" class="btn btn-inverse"> @lang('buttons.general.back')</a>
                    </div>

                </form>
            
            
            </div>
        </div>
    </div>
    
@endsection