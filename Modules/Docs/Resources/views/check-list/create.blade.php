@extends('layouts.app')




@section('page_title', __('page_titles.docs.check-list-item-norma.create'))


@section('breadcrumbs')

    <li class="breadcrumb-item"><a href="{{ route('core.home') }}"> @lang('page_titles.general.home') </a></li>
    <li class="breadcrumb-item"><a href="{{ route('docs.norma.item-norma.check-list', ['norma_id' => $itemNorma->docsNorma->id, 'item_norma_id' => $itemNorma->id]) }}"> @lang('page_titles.docs.check-list-item-norma.index') </a></li>
    <li class="breadcrumb-item active"> @lang('page_titles.docs.check-list-item-norma.create') </li>    

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

                <form method="POST" action="{{ route('docs.norma.item-norma.check-list.salvar', ['norma_id' => $itemNorma->docsNorma->id, 'item_norma_id' => $itemNorma->id]) }}"> 
                    {{ csrf_field() }}
                    
                    @component(
                        'docs::components.check-list', 
                        [
                            'checkListEdit' => [],
                            'descricao' => '',
                        ]
                    )
                    @endcomponent
                        
                    <div class="form-actions ">
                        <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> @lang('buttons.general.save')</button>
                        <a href="{{ route('docs.norma.item-norma.check-list', ['norma_id' => $itemNorma->docsNorma->id, 'item_norma_id' => $itemNorma->id]) }}" class="btn btn-inverse"> @lang('buttons.general.back')</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
    
@endsection