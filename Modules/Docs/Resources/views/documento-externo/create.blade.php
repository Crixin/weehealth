@extends('layouts.app')

@section('page_title', __('page_titles.docs.documento-externo.create'))


@section('breadcrumbs')

    <li class="breadcrumb-item"><a href="{{ route('core.home') }}"> @lang('page_titles.general.home') </a></li>
    <li class="breadcrumb-item"><a href="{{ route('docs.documento-externo') }}"> @lang('page_titles.docs.documento-externo.index') </a></li>
    <li class="breadcrumb-item active"> @lang('page_titles.docs.documento-externo.create') </li>    

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

                <form method="POST" action="{{ route('docs.documento-externo.salvar') }}"> 
                    {{ csrf_field() }}
                    
                    @component(
                        'docs::components.documento-externo', 
                        [
                            'documentoExternoEdit' => [],
                            'setores' => $setores,
                            'fornecedores' => $fornecedores,
                            'versao' => '1.0',
                            'validade' => '',
                            'lido' => false
                            
                        ]
                    )
                    @endcomponent
                        
                    <div class="form-actions ">
                        <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> @lang('buttons.general.save')</button>
                        <a href="{{ route('docs.documento-externo') }}" class="btn btn-inverse"> @lang('buttons.general.back')</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
    
@endsection