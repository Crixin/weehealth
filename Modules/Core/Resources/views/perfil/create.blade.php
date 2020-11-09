@extends('core::layouts.app')

@extends('core::layouts.menuPortal')
@yield('menu')

@section('page_title', __('page_titles.perfil.create'))

@section('breadcrumbs')

    <li class="breadcrumb-item"><a href="{{ route('home') }}"> @lang('page_titles.general.home') </a></li>
    <li class="breadcrumb-item"><a href="{{ route('perfil') }}"> @lang('page_titles.perfil.index') </a></li>
    <li class="breadcrumb-item active"> @lang('page_titles.perfil.create') </li>    

@endsection

@section('content')

    <div class="col-12">
        <div class="card">
            <div class="card-body">
                @if(Session::has('message'))
                    @component('core::componentes.alert') @endcomponent
                    {{ Session::forget('message') }}
                @endif
                
                <form method="POST" action="{{ route('perfil.salvar') }}">
                    {{ csrf_field() }}
                    <div class="form-body">
                        <div class="row p-t-20">
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('nome') ? ' has-error' : '' }}">
                                    <label class="control-label">Nome</label>
                                    <input type="text" id="nome" name="nome" value="{{ old('nome') }}" class="form-control" required autofocus>
                                    <small class="form-control-feedback"> Digite o nome do perfil. </small> 
                                    @if ($errors->has('nome'))
                                        <br/>    
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('nome') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('permissoes') ? ' has-error' : '' }}">
                                    <label class="control-label">Permissões</label>
                                    <select class="form-control selectpicker" name="permissoes[]" id="permissoes" required data-size="10" data-live-search="true" data-actions-box="true" multiple>
                                        @foreach ($permissoes as $permissao)
                                            <option value="{{$permissao->id}}" > {{$permissao->descricao }} </option>
                                        @endforeach
                                    </select>
                                    <small class="form-control-feedback"> Selecione as permissões. </small> 

                                    @if ($errors->has('permissoes'))
                                        <br/>
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('permissoes') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> @lang('buttons.general.save')</button>
                        <a href="{{ route('perfil') }}" class="btn btn-inverse"> @lang('buttons.general.back')</a>
                    </div>
                </form>
            
            </div>
        </div>
    </div>
    
@endsection
