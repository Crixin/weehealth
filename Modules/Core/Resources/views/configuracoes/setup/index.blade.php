@extends('core::layouts.app')

@extends('core::layouts.menuCore')
@yield('menu')


@section('page_title', __('page_titles.configs.index_setup'))


@section('breadcrumbs')

    <li class="breadcrumb-item"><a href="{{ route('core.home') }}"> @lang('page_titles.general.home') </a></li>
    <li class="breadcrumb-item active"> @lang('page_titles.configs.index_setup') </li>    

@endsection



@section('content')

<div class="col-12">
    <div class="card">
        <div class="card-body">
            @if(Session::has('message'))
                @component('core::componentes.alert')
                @endcomponent
                
                {{ Session::forget('message') }}
            @endif

            <form method="POST" action="{{ route('configuracao.setup.alterar') }}" enctype="multipart/form-data">
                {{ csrf_field() }}

                <div class="form-body">
                    <h3 class="box-title"> @lang('page_titles.configs.index_setup') </h3>
                    <hr class="m-t-0 m-b-10">

                    <div class="row p-t-20">

                        <div class="col-md-6">
                            <div class="form-group{{ $errors->has('logo_login') ? ' has-error' : '' }}">
                                <label class="control-label">Logo do login</label>
                                <input type="file" id="logo_login" name="logo_login" class="form-control" >
                                <small class="form-control-feedback"> São permitidos os formatos jpeg, png e jpg </small> 
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group{{ $errors->has('logo_sistema') ? ' has-error' : '' }}">
                                <label class="control-label">Logo do sistema</label>
                                <input type="file" id="logo_sistema" name="logo_sistema"  class="form-control" >
                                <small class="form-control-feedback"> São permitidos os formatos jpeg, png e jpg </small> 
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> @lang('buttons.general.save')</button>
                    {{-- <a href="{{ route('home') }}" class="btn btn-inverse"> @lang('buttons.general.back')</a> --}}
                </div>

            </form>

        </div>
    </div>
</div>
    
@endsection


@section('footer')


@endsection