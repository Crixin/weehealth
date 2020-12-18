@extends('layouts.app')

@section('page_title', __('page_titles.core.perfil.create'))

@section('breadcrumbs')

    <li class="breadcrumb-item"><a href="{{ route('core.home') }}"> @lang('page_titles.general.home') </a></li>
    <li class="breadcrumb-item"><a href="{{ route('core.perfil') }}"> @lang('page_titles.core.perfil.index') </a></li>
    <li class="breadcrumb-item active"> @lang('page_titles.core.perfil.update') </li>    

@endsection

@section('content')

    <div class="col-12">
        <div class="card">
            <div class="card-body">

            @component('components.validation-error', ['errors']) @endcomponent

            @if(Session::has('message'))
                @component('components.alert') @endcomponent
                {{ Session::forget('message') }}
            @endif
            
            <form method="POST" action="{{ route('core.perfil.alterar', ['id' => $perfil->id]) }}">
                {{ Form::token() }}
                
                @component(
                    'core::components.perfil',
                    [
                        'nome' => $perfil->nome,
                        'modules' => $modules,
                        'permissoes' => $perfil->permissoes
                    ]
                )
                @endcomponent

                <div class="form-actions">
                    <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> @lang('buttons.general.save')</button>
                    <a href="{{ route('core.perfil') }}" class="btn btn-inverse"> @lang('buttons.general.back')</a>
                </div>
            </form>
            
            </div>
        </div>
    </div>
    
@endsection
