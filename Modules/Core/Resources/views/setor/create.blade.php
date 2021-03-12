@extends('layouts.app')

@extends('layouts.menuCore')
@yield('menu')


@section('page_title', __('page_titles.core.setor.create'))


@section('breadcrumbs')

    <li class="breadcrumb-item"><a href="{{ route('core.home') }}"> @lang('page_titles.general.home') </a></li>
    <li class="breadcrumb-item"><a href="{{ route('core.setor') }}"> @lang('page_titles.core.setor.index') </a></li>
    <li class="breadcrumb-item active"> @lang('page_titles.core.setor.create') </li>    

@endsection



@section('content')

    <div class="col-12">
        <div class="card">
            <div class="card-body">


                @component('components.validation-error', ['errors']) @endcomponent
                
                @if(Session::has('message'))
                    @component('components.alert')@endcomponent

                    {{ Session::forget('message') }}
                @endif

                <form method="POST" action="{{ route('core.setor.salvar') }}">
                    {{ csrf_field() }}
                    @component(
                        'core::components.setor', 
                        [
                            'setorEdit' => [],
                            'nome' => '',
                            'descricao' => '',
                            'sigla' => '',
                            'achouDocumentos' => false
                        ]
                    )
                    @endcomponent
                    <div class="form-actions">
                        <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> @lang('buttons.general.save')</button>
                        <a href="{{ route('core.setor') }}" class="btn btn-inverse"> @lang('buttons.general.back')</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
    
@endsection