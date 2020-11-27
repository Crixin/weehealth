@extends('layouts.app')

@extends('layouts.menuDocs')
@yield('menu')


@section('page_title', __('page_titles.docs.tipo-documento.create'))


@section('breadcrumbs')

    <li class="breadcrumb-item"><a href="{{ route('core.home') }}"> @lang('page_titles.general.home') </a></li>
    <li class="breadcrumb-item"><a href="{{ route('docs.tipo-documento') }}"> @lang('page_titles.docs.tipo-documento.index') </a></li>
    <li class="breadcrumb-item active"> @lang('page_titles.docs.tipo-documento.create') </li>    

@endsection



@section('content')

    <div class="col-12">
        <div class="card">
            <div class="card-body">


                @if(Session::has('message'))
                    @component('componentes.alert')@endcomponent

                    {{ Session::forget('message') }}
                @endif

                <form method="POST" action="{{ route('docs.tipo-documento.salvar') }}">
                    {{ csrf_field() }}
                    <div class="form-body">
                        
                        <div class="row p-t-20">
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('nome') ? ' has-error' : '' }}">
                                    <label class="control-label">Nome</label>
                                    <input type="text" id="nome" name="nome" value="{{ old('nome') }}" class="form-control" required autofocus>
                                    <small class="form-control-feedback"> Digite o nome do plano que será criado. </small> 

                                    @if ($errors->has('nome'))
                                        <br/>    
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('nome') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
                                    <label class="control-label">Status</label>
                                   
                                    <td class="text-center text-nowrap">
                                        <div class="switch">
                                            <label>Inativo
                                                <input name="status" id="status" type="checkbox" class="switch-elaborador"  checked ><span class="lever switch-col-light-blue"></span>Ativo
                                            </label>
                                        </div>
                                    </td>
                                    @if ($errors->has('status'))
                                        <br/>
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('status') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> @lang('buttons.general.save')</button>
                        <a href="{{ route('docs.tipo-documento') }}" class="btn btn-inverse"> @lang('buttons.general.back')</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
    
@endsection