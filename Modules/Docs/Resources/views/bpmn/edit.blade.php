@extends('layouts.app')




@section('page_title', __('page_titles.docs.bpmn.update'))


@section('breadcrumbs')

    <li class="breadcrumb-item"><a href="{{ route('docs.home') }}"> @lang('page_titles.general.home') </a></li>
    <li class="breadcrumb-item"><a href="{{ route('docs.bpmn') }}"> @lang('page_titles.docs.bpmn.index') </a></li>
    <li class="breadcrumb-item active"> @lang('page_titles.docs.bpmn.update') </li>    

@endsection



@section('content')

    <div class="col-12">
        <div class="card">
            <div class="card-body">


                @if(Session::has('message'))
                    @component('components.alert')@endcomponent

                    {{ Session::forget('message') }}
                @endif
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    Ao salvar alguma alteração no bpmn, será gerado uma nova versão do mesmo.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                    </button>
               </div>
                <form method="POST" action="{{ route('docs.bpmn.alterar') }}">
                    {{ csrf_field() }}
                    <input type="hidden" name="idBPMN" id="idBPMN" value="{{ $bpmn->id }}">
                    
                    @component(
                        'docs::components.bpmn', 
                        [
                            'bpmnEdit' => $bpmn,
                            'nome'     => $bpmn->nome,
                            'versao'   => $bpmn->versao 
                        ]
                    )
                    @endcomponent
                    <div class="col-md-12 mt-5">
                        <div class="pull-right">
                            <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> @lang('buttons.general.save')</button>
                            <a href="{{ route('docs.bpmn') }}" class="btn btn-inverse"> @lang('buttons.general.back')</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
@endsection

