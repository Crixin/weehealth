@extends('layouts.app')

@extends('layouts.menuDocs')
@yield('menu')


@section('page_title', __('page_titles.docs.opcao-controle-registro.update'))


@section('breadcrumbs')

    <li class="breadcrumb-item"><a href="{{ route('docs.home') }}"> @lang('page_titles.general.home') </a></li>
    <li class="breadcrumb-item"><a href="{{ route('docs.opcao-controle') }}"> @lang('page_titles.docs.opcao-controle-registro.index') </a></li>
    <li class="breadcrumb-item active"> @lang('page_titles.docs.opcao-controle-registro.update') </li>    

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

                <form method="POST" action="{{ route('docs.opcao-controle.alterar') }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="idOpcaoControle" value="{{ $opcao->id }}">
                    @component(
                        'docs::components.opcao-controle-registro', 
                        [
                            'opcaoControleEdit' => $opcao,
                            'descricao' => $opcao->descricao, 
                            'tipos' => $tipos
                        ]
                    )
                    @endcomponent
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> @lang('buttons.general.save')</button>
                        <a href="{{ route('docs.opcao-controle') }}" class="btn btn-inverse"> @lang('buttons.general.back')</a>
                    </div>

                </form>

            </div>
        </div>
    </div>
    
@endsection