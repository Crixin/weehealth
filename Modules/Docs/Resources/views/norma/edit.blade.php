@extends('layouts.app')




@section('page_title', __('page_titles.docs.norma.update'))


@section('breadcrumbs')

    <li class="breadcrumb-item"><a href="{{ route('docs.home') }}"> @lang('page_titles.general.home') </a></li>
    <li class="breadcrumb-item"><a href="{{ route('docs.norma') }}"> @lang('page_titles.docs.norma.index') </a></li>
    <li class="breadcrumb-item active"> @lang('page_titles.docs.norma.update') </li>    

@endsection



@section('content')
@include('docs::modal/item-norma',
[
    "itens"          => $itens
])
    <div class="col-12">
        <div class="card">
            <div class="card-body">


                @component('components.validation-error', ['errors'])@endcomponent

                @if(Session::has('message'))
                    @component('components.alert')@endcomponent

                    {{ Session::forget('message') }}
                @endif

                <form method="POST" action="{{ route('docs.norma.alterar') }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="idNorma" value="{{ $norma->id }}">
                    <input type="hidden" name="ordemHidden" id="ordemHidden" value="{{$norma->docsItemNorma->count() ?? 0}}"> 
                    @component(
                        'docs::components.norma', 
                        [
                            'normaEdit' => $norma,
                            'descricao' => $norma->descricao, 
                            'orgaos' => $orgaos,
                            'ciclos' => $ciclos,
                            'itens'  => $itens
                        ]
                    )
                    @endcomponent
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> @lang('buttons.general.save')</button>
                        <a href="{{ route('docs.norma') }}" class="btn btn-inverse"> @lang('buttons.general.back')</a>
                    </div>

                </form>

            </div>
        </div>
    </div>
    
@endsection
