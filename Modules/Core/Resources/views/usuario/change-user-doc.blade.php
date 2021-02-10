@extends('layouts.app')

@section('page_title', __('page_titles.core.user.change-user'))

@section('breadcrumbs')

    <li class="breadcrumb-item"><a href="{{ route('core.home') }}"> @lang('page_titles.general.home') </a></li>
    <li class="breadcrumb-item"><a href="{{ route('core.usuario') }}"> @lang('page_titles.core.user.index') </a></li>
    <li class="breadcrumb-item active"> @lang('page_titles.core.user.change-user') </li>    

@endsection



@section('content')

    <div class="col-12">
        <div class="card">
            <div class="card-body">
            
                @if(Session::has('message'))
                    @component('components.alert')
                    @endcomponent
                    
                    {{ Session::forget('message') }}
                @endif

                <form method="POST" action="" onsubmit="msgConfirmacao()" id="formSubstituir" name="formSubstituir" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <legend>@lang('page_titles.core.user.change-user') <b>{{$usuario->name}}</b></legend>
                    <hr>
                    <div class="col-md-12">
                        @component(
                            'core::components.substituir-usuario-documento', 
                            [
                                'idUsuario' => $usuario->id,
                                'grupos' => $grupos,
                                'documentos' => [],
                                'usuariosSubstituto' => []
                            ]
                        )
                        @endcomponent
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-success"> <i class="mdi mdi-link-off"></i> @lang('buttons.general.unlink')</button>
                        <a href="{{ route('core.usuario') }}" class="btn btn-inverse"> @lang('buttons.general.back')</a>
                    </div>

                </form>
            </div>
        </div>
    </div>
    
@endsection