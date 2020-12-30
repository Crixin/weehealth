@extends('layouts.app')

@extends('layouts.menuCore')
@yield('menu')


@section('page_title', __('page_titles.core.modelo-notificacao.create'))


@section('breadcrumbs')

    <li class="breadcrumb-item"><a href="{{ route('core.home') }}"> @lang('page_titles.general.home') </a></li>
    <li class="breadcrumb-item"><a href="{{ route('core.modelo-notificacao') }}"> @lang('page_titles.core.modelo-notificacao.index') </a></li>
    <li class="breadcrumb-item active"> @lang('page_titles.core.modelo-notificacao.create') </li>    

@endsection



@section('content')

    <div class="col-12">
        <div class="card">
            <div class="card-body">


                @component('components.validation-error', ['errors']) @endcomponent

                <form method="POST" action="{{ route('core.modelo-notificacao.salvar') }}">
                    {{ csrf_field() }}
                    @component(
                        'core::components.notificacao', 
                        [
                            'notificacaoEdit' => [],
                            'nome' => '',
                            'titulo' => '',
                            'corpo' => '',
                            'tiposEnvio' => $tiposEnvio
                        ]
                    )
                    @endcomponent
                    <div class="form-actions">
                        <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> @lang('buttons.general.save')</button>
                        <a href="{{ route('core.modelo-notificacao') }}" class="btn btn-inverse"> @lang('buttons.general.back')</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
    
@endsection