@extends('layouts.app')




@section('page_title', __('page_titles.core.modelo-notificacao.update'))


@section('breadcrumbs')

    <li class="breadcrumb-item"><a href="{{ route('core.home') }}"> @lang('page_titles.general.home') </a></li>
    <li class="breadcrumb-item"><a href="{{ route('core.modelo-notificacao') }}"> @lang('page_titles.core.modelo-notificacao.index') </a></li>
    <li class="breadcrumb-item active"> @lang('page_titles.core.modelo-notificacao.update') </li>    

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
                

                <form method="POST" action="{{ route('core.modelo-notificacao.alterar') }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="idModeloNotificacao" value="{{ $modeloNotificacao->id }}">
                    @component(
                        'core::components.notificacao', 
                        [
                            'notificacaoEdit' => $modeloNotificacao,
                            'nome' => $modeloNotificacao->nome,
                            'titulo' => $modeloNotificacao->titulo_email,
                            'corpo' => $modeloNotificacao->corpo_email,
                            'delay' => $modeloNotificacao->tempo_delay_envio,
                            'tentativasEnvio' => $modeloNotificacao->numero_tentativas_envio,
                            'tiposEnvio' => $tiposEnvio,
                            'tiposNotificacao' => $tiposNotificacao
                        ]
                    )
                    @endcomponent
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> @lang('buttons.general.save')</button>
                        <a href="{{ route('core.modelo-notificacao') }}" class="btn btn-inverse"> @lang('buttons.general.back')</a>
                    </div>

                </form>

            </div>
        </div>
    </div>
    
@endsection