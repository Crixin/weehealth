@extends('layouts.app')




@section('page_title', __('page_titles.docs.plano.update'))


@section('breadcrumbs')

    <li class="breadcrumb-item"><a href="{{ route('docs.home') }}"> @lang('page_titles.general.home') </a></li>
    <li class="breadcrumb-item"><a href="{{ route('docs.plano') }}"> @lang('page_titles.docs.plano.index') </a></li>
    <li class="breadcrumb-item active"> @lang('page_titles.docs.plano.update') </li>    

@endsection



@section('content')

    <div class="col-12">
        <div class="card">
            <div class="card-body">


                @if(Session::has('message'))
                    @component('components.alert')@endcomponent

                    {{ Session::forget('message') }}
                @endif

                <form method="POST" action="{{ route('docs.plano.alterar') }}">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{ $plano->id }}">
                    
                    @component(
                        'docs::components.plano', 
                        [
                            'planoEdit' => $plano,
                            'nome' => $plano->nome, 
                            'status' => $plano->status
                        ]
                    )
                    @endcomponent

                    <div class="form-actions">
                        <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> @lang('buttons.general.save')</button>
                        <a href="{{ route('docs.plano') }}" class="btn btn-inverse"> @lang('buttons.general.back')</a>
                    </div>

                </form>

            </div>
        </div>
    </div>
    
@endsection