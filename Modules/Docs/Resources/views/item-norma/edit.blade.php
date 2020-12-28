@extends('layouts.app')




@section('page_title', __('page_titles.docs.item-norma.update'))


@section('breadcrumbs')

    <li class="breadcrumb-item"><a href="{{ route('docs.home') }}"> @lang('page_titles.general.home') </a></li>
    <li class="breadcrumb-item"><a href="{{ route('docs.norma.item-norma', ['id' => $itemNormaEdit->id, 'norma_id' => $itemNormaEdit->docsNorma->id] ) }}"> @lang('page_titles.docs.item-norma.index') </a></li>
    <li class="breadcrumb-item active"> @lang('page_titles.docs.item-norma.update') </li>    

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

                <form method="POST" action="{{ route('docs.norma.item-norma.alterar', ['id' => $itemNormaEdit->id, 'norma_id' => $itemNormaEdit->docsNorma->id]) }}" >
                    {{ csrf_field() }}
                    <input type="hidden" name="idItemNorma" value="{{ $itemNormaEdit->id }}">
                    <input type="hidden" name="idNorma" value="{{$itemNormaEdit->norma_id}}">
                    @component(
                        'docs::components.item-norma', 
                        [
                            'descricao' => $itemNormaEdit->descricao,
                            'numero' => $itemNormaEdit->numero
                        ]
                    )
                    @endcomponent
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> @lang('buttons.general.save')</button>
                        <a href="{{ route('docs.norma.item-norma', ['norma_id' => $itemNormaEdit->docsNorma->id]) }}" class="btn btn-inverse"> @lang('buttons.general.back')</a>
                    </div>

                </form>

            </div>
        </div>
    </div>
    
@endsection