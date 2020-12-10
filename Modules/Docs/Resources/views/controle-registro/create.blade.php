@extends('layouts.app')

@extends('layouts.menuDocs')
@yield('menu')


@section('page_title', __('page_titles.docs.controle-registro.create'))


@section('breadcrumbs')

    <li class="breadcrumb-item"><a href="{{ route('core.home') }}"> @lang('page_titles.general.home') </a></li>
    <li class="breadcrumb-item"><a href="{{ route('docs.controle-registro') }}"> @lang('page_titles.docs.controle-registro.index') </a></li>
    <li class="breadcrumb-item active"> @lang('page_titles.docs.controle-registro.create') </li>    

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

                <form method="POST" action="{{ route('docs.controle-registro.salvar') }}"> 
                    {{ csrf_field() }}
                    
                    @component(
                        'docs::components.controle-registro', 
                        [
                            'controleRegistroEdit' => [],
                            'codigo' => '',
                            'descricao' => '',
                            'responsaveis' => $responsaveis,
                            'meios' => $meios,
                            'meiosArmazenamento' => $meiosArmazenamento,
                            'meiosProtecao' => $meiosProtecao,
                            'meiosRecuperacao' => $meiosRecuperacao,
                            'niveisAcesso' => $niveisAcesso,
                            'opcoesRetencaoLocal' => $opcoesRetencaoLocal,
                            'opcoesRetencaoDeposito' => $opcoesRetencaoDeposito,
                            'disposicoes' => $disposicoes,
                        ]
                    )
                    @endcomponent
                        
                    <div class="form-actions ">
                        <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> @lang('buttons.general.save')</button>
                        <a href="{{ route('docs.controle-registro') }}" class="btn btn-inverse"> @lang('buttons.general.back')</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
    
@endsection