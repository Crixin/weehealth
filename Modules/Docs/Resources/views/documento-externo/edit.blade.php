@extends('layouts.app')




@section('page_title', __('page_titles.docs.documento-externo.update'))


@section('breadcrumbs')

    <li class="breadcrumb-item"><a href="{{ route('docs.home') }}"> @lang('page_titles.general.home') </a></li>
    <li class="breadcrumb-item"><a href="{{ route('docs.documento-externo') }}"> @lang('page_titles.docs.documento-externo.index') </a></li>
    <li class="breadcrumb-item active"> @lang('page_titles.docs.documento-externo.update') </li>    

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

                <form method="POST" action="{{ route('docs.documento-externo.alterar') }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="idDocumento" value="{{ $documento->id }}">
                    @component(
                        'docs::components.documento-externo', 
                        [
                            'documentoExternoEdit' => $documento,
                            'setores' => $setores,
                            'fornecedores' => $fornecedores,
                            'versao' => $documento->revisao,
                            'validade' => $documento->validade,
                            'lido' => $documento->validado
                        ]
                    )
                    @endcomponent
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> @lang('buttons.general.save')</button>
                        <a href="{{ route('docs.documento-externo') }}" class="btn btn-inverse"> @lang('buttons.general.back')</a>
                    </div>

                </form>

            </div>
        </div>
    </div>
    
@endsection