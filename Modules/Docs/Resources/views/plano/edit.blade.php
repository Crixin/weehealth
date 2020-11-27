@extends('layouts.app')

@extends('layouts.menuDocs')
@yield('menu')


@section('page_title', __('page_titles.docs.plano.update'))


@section('breadcrumbs')

    <li class="breadcrumb-item"><a href="{{ route('docs.home') }}"> @lang('page_titles.general.home') </a></li>
    <li class="breadcrumb-item"><a href="{{ route('docs.plano') }}"> @lang('page_titles.docs.plano.index') </a></li>
    <li class="breadcrumb-item active"> @lang('page_titles.docs.plano.update') </li>    

@endsection



@section('content')

    <div class="col-12">
        <div class="card">
            <div class="card-body">


                @if(Session::has('message'))
                    @component('componentes.alert')@endcomponent

                    {{ Session::forget('message') }}
                @endif

                <form method="POST" action="{{ route('docs.plano.alterar') }}">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{ $plano->id }}">
                    
                    <div class="form-body">
                        <div class="row p-t-20">
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('nome') ? ' has-error' : '' }}">
                                    <label class="control-label">Nome</label>
                                    <input type="text" id="nome" name="nome" value="{{ $plano->nome }}" class="form-control" required autofocus>
                                    <small class="form-control-feedback"> Digite o novo nome para o plano. </small> 

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
                                                <input name="status" id="status" type="checkbox" class="switch-elaborador"  @if ($plano->ativo == true) checked @endif ><span class="lever switch-col-light-blue"></span>Ativo
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
                        <a href="{{ route('docs.plano') }}" class="btn btn-inverse"> @lang('buttons.general.back')</a>
                    </div>

                </form>

            </div>
        </div>
    </div>
    
@endsection