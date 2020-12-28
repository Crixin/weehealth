@extends('layouts.app')

@extends('layouts.menuCore')
@yield('menu')


@section('page_title', __('page_titles.core.parametro.update'))


@section('breadcrumbs')

    <li class="breadcrumb-item"><a href="{{ route('core.home') }}"> @lang('page_titles.general.home') </a></li>
    <li class="breadcrumb-item"><a href="{{ route('core.configuracao.parametros') }}"> @lang('page_titles.core.parametro.index') </a></li>
    <li class="breadcrumb-item active"> @lang('page_titles.core.parametro.update') </li>    

@endsection



@section('content')

    <div class="col-12">
        <div class="card">
            <div class="card-body">


                @component('components.validation-error', ['errors'])@endcomponent

                @if(Session::has('message'))
                    @component('components.alert')@endcomponent

                    {{ Session::forget('message') }}
                @endif

                <form method="POST" action="{{ route('core.configuracao.parametros.alterar') }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="idParametro" value="{{ $parametro->id }}">
                    @component(
                        'core::components.parametro', 
                        [
                            'parametroEdit' => $parametro,
                        ]
                    )
                    @endcomponent
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> @lang('buttons.general.save')</button>
                        <a href="{{ route('core.configuracao.parametros') }}" class="btn btn-inverse"> @lang('buttons.general.back')</a>
                    </div>

                </form>

            </div>
        </div>
    </div>
    
@endsection