@extends('layouts.app')

@extends('layouts.menuDocs')
@yield('menu')


@section('page_title', __('page_titles.docs.fluxo.create'))


@section('breadcrumbs')

    <li class="breadcrumb-item"><a href="{{ route('core.home') }}"> @lang('page_titles.general.home') </a></li>
    <li class="breadcrumb-item"><a href="{{ route('docs.fluxo') }}"> @lang('page_titles.docs.fluxo.index') </a></li>
    <li class="breadcrumb-item active"> @lang('page_titles.docs.fluxo.create') </li>    

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

                <form method="POST" action="{{ route('docs.fluxo.salvar') }}"> 
                    {{ csrf_field() }}
                    
                    @component(
                        'docs::components.fluxo', 
                        [
                            'fluxoEdit' => [],
                            'nome' => '',
                            'descricao' => '', 
                            'versao' => '',
                            'grupos' => $grupos,
                            'perfis' => $perfis
                        ]
                    )
                    @endcomponent
                        
                    <div class="form-actions ">
                        <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> @lang('buttons.general.save')</button>
                        <a href="{{ route('docs.fluxo') }}" class="btn btn-inverse"> @lang('buttons.general.back')</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
    
@endsection