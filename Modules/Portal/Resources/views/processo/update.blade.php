@extends('layouts.app')




@section('page_title', __('page_titles.portal.process.update'))


@section('breadcrumbs')

    <li class="breadcrumb-item"><a href="{{ route('portal.home') }}"> @lang('page_titles.general.home') </a></li>
    <li class="breadcrumb-item"><a href="{{ route('portal.processo') }}"> @lang('page_titles.portal.process.index') </a></li>
    <li class="breadcrumb-item active"> @lang('page_titles.portal.process.update') </li>    

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

                <form method="POST" action="{{ route('portal.processo.alterar') }}">
                    {{ csrf_field() }}
                    <input type="hidden" name="idProcesso" value="{{ $processo->id }}">
                    
                    <div class="form-body">
                        <div class="row p-t-20">
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('nome') ? ' has-error' : '' }}">
                                    <label class="control-label">Nome</label>
                                    <input type="text" id="nome" name="nome" value="{{ $processo->nome }}" class="form-control" required autofocus>
                                    <small class="form-control-feedback"> Digite o nome do processo que será criado. </small> 

                                    @if ($errors->has('nome'))
                                        <br/>    
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('nome') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('descricao') ? ' has-error' : '' }}">
                                    <label class="control-label">Descrição</label>
                                    <input type="text" id="descricao" class="form-control" name="descricao" value="{{ $processo->descricao }}" required>
                                    <small class="form-control-feedback"> Descreva, brevemente, qual a função deste processo, de modo a facilitar a compreensão. </small> 

                                    @if ($errors->has('descricao'))
                                        <br/>
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('descricao') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> @lang('buttons.general.save')</button>
                        <a href="{{ route('portal.processo') }}" class="btn btn-inverse"> @lang('buttons.general.back')</a>
                    </div>

                </form>

            </div>
        </div>
    </div>
    
@endsection