@extends('layouts.app')

@extends('layouts.menuDocs')
@yield('menu')


@section('page_title', __('page_titles.docs.controle-registro.update'))


@section('breadcrumbs')

    <li class="breadcrumb-item"><a href="{{ route('docs.home') }}"> @lang('page_titles.general.home') </a></li>
    <li class="breadcrumb-item"><a href="{{ route('docs.controle-registro') }}"> @lang('page_titles.docs.controle-registro.index') </a></li>
    <li class="breadcrumb-item active"> @lang('page_titles.docs.controle-registro.update') </li>    

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

                <form method="POST" action="{{ route('docs.controle-registro.alterar') }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="idControleRegistro" value="{{ $controleRegistro->id }}">
                    @component(
                        'docs::components.controle-registro', 
                        [
                            'controleRegistroEdit' => $controleRegistro,
                            'descricao' => $controleRegistro->descricao, 
                        ]
                    )
                    @endcomponent
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> @lang('buttons.general.save')</button>
                        <a href="{{ route('docs.controle-registro') }}" class="btn btn-inverse"> @lang('buttons.general.back')</a>
                    </div>

                </form>

            </div>
        </div>
    </div>
    
@endsection