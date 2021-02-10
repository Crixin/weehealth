@extends('layouts.app')

@section('page_title', __('page_titles.core.user.change-user'))

@section('breadcrumbs')

    <li class="breadcrumb-item"><a href="{{ route('core.home') }}"> @lang('page_titles.general.home') </a></li>
    <li class="breadcrumb-item"><a href="{{ route('core.usuario') }}"> @lang('page_titles.core.user.index') </a></li>
    <li class="breadcrumb-item active"> @lang('page_titles.core.user.change-user') </li>    

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

                <form method="POST" action="{{route('core.usuario.substituir-modulo')}}"  id="formSubstituir" name="formSubstituir" >
                    {{ csrf_field() }}
                    <input type="hidden" name="id" id="id" value="{{$usuario->id}}">

                    <legend>@lang('page_titles.core.user.change-user') <b>{{$usuario->name}}</b></legend>
                    <hr>

                    <div class="col-md-6">
                        <div class="form-group required{{ $errors->has('modulos') ? ' has-error' : '' }}">
                            {!! Form::label('modulos', 'Módulo',['class' => 'control-label']) !!}
                            {!! Form::select('modulos',$modulos, null, ['id' => 'modulos', 'class' => 'form-control selectpicker','data-live-search' => 'true', 'data-actions-box' => 'true', 'required' => 'required', 'placeholder' => __('components.selectepicker-default')]) !!}
                            <small class="text-danger">{{ $errors->first('modulos') }}</small>
                        </div>
                    </div>    

                    <div class="form-actions">
                        <button type="submit" class="btn btn-success"> <i class="mdi mdi-chevron-double-right"></i> @lang('buttons.general.next')</button>
                        <a href="{{ route('core.usuario') }}" class="btn btn-inverse"> @lang('buttons.general.back')</a>
                    </div>

                </form>
            </div>
        </div>
    </div>
    
@endsection