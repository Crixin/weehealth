@extends('layouts.app')



@section('page_title', __('page_titles.docs.bpmn.create'))


@section('breadcrumbs')

    <li class="breadcrumb-item"><a href="{{ route('docs.home') }}"> @lang('page_titles.general.home') </a></li>
    <li class="breadcrumb-item active">@lang('page_titles.docs.bpmn.create') </li>    

    
@endsection

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-body">
            <!-- Start Page Content -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            @component('components.validation-error', ['errors'])@endcomponent

                            @if(Session::has('message'))
                                @component('components.alert')@endcomponent
        
                                {{ Session::forget('message') }}
                            @endif

                            <form method="POST" action="{{ route('docs.bpmn.salvar') }}" id="formBpmn" name="formBpmn">     
                                {{ csrf_field() }}
                                
                                @component(
                                    'docs::components.bpmn', 
                                    [
                                        'bpmnEdit' => [],
                                        'nome' => '',
                                        'versao' => '1',
                                    ]
                                )
                                @endcomponent
                                
                                <div class="col-md-12 mt-5">
                                    <div class="pull-right">
                                        <button type="button" id="btn-bpmn" class="btn btn-success">@lang('buttons.general.save')</button>
                                        <a href="{{ route('docs.bpmn') }}" class="btn btn-inverse"> @lang('buttons.general.back')</a>
                                    </div>
                                </div>
                            </form>    
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Page Content -->
        </div>
    </div>
</div>
@endsection

