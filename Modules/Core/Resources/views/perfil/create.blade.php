@extends('layouts.app')

@extends('layouts.menuCore')
@yield('menu')

@section('page_title', __('page_titles.core.perfil.create'))

@section('breadcrumbs')

    <li class="breadcrumb-item"><a href="{{ route('core.home') }}"> @lang('page_titles.general.home') </a></li>
    <li class="breadcrumb-item"><a href="{{ route('core.perfil') }}"> @lang('page_titles.core.perfil.index') </a></li>
    <li class="breadcrumb-item active"> @lang('page_titles.core.perfil.create') </li>    

@endsection

@section('content')

    <div class="col-12">
        <div class="card">
            <div class="card-body">
                @php
                $nome = "adasasdsaSlider";
                $modules = ['Core'];
                @endphp
{{--                 @if(Session::has('message'))
                    @component('components.alert') @endcomponent
                    {{ Session::forget('message') }}
                @endif --}}
              {{--   @php
                    dd(Session::get('message')->all());
                @endphp --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form method="POST" action="{{ route('perfil.salvar') }}">
                    {{ Form::token() }}
                        
                    
                    @component('core::components.perfil')
                        @slot('nome') {{ $nome }} @endslot
                        @slot('modules') {{ $modules }} @endslot
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
