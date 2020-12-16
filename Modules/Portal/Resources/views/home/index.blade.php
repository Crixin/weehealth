@extends('layouts.app')



@section('page_title', 'Página Inicial')

@section('breadcrumbs')
    <li class="breadcrumb-item active"><a href="javascript:void(0)">Página Inicial</a></li>
@endsection


@section('content')

<div class="col-12">
    <div class="card">
        <div class="card-body">
            
            <p class="text-center  font-weight-bold" style="font-size: xx-large;">
                {{ \Auth::user()->name }}
                <p class="text-center text-info" style="font-size: larger;"> @lang('home.welcome') </p>
            </p>

            <hr>

            <div class="row">
                <div class="col-md-6">
                    <h4> @lang('home.title_personal_information') </h4>
                    <blockquote>
                        <ul class="list-icons">
                            <li><a href="javascript:void(0)" class="cursor-default"><i class="fa fa-chevron-right text-success"></i> @lang('home.email'): <span class="font-weight-bold">{{ Auth::user()->email }}</span> </a></li>
                            <li><a href="javascript:void(0)" class="cursor-default"><i class="fa fa-chevron-right text-success"></i> @lang('home.user'): <span class="font-weight-bold">{{ Auth::user()->username }}</span> </a></li>
                            <li><a href="javascript:void(0)" class="cursor-default"><i class="fa fa-chevron-right text-success"></i> @lang('home.administrator'): <span class="font-weight-bold">{{ Auth::user()->administrador ? 'SIM' : 'NÃO' }}</span> </a></li>
                        </ul>
                    </blockquote>
                </div>
                <div class="col-md-6">
                    <h4> @lang('home.title_current_configs') </h4>
                    <blockquote>
                        <ul class="list-icons">
                            <li><a href="javascript:void(0)" class="cursor-default"><i class="fa fa-check text-success"></i> @lang('home.permissions_level'): <span class="font-weight-bold">{{ (Auth::user()->utilizar_permissoes_nivel_usuario) ? 'USUÁRIO' : 'GRUPO'  }}</span> </a></li>
                            @if (Auth::user()->utilizar_permissoes_nivel_usuario)
                                @foreach (Helper::getUserEnterprises() as $empresa)
                                    <li><a href="javascript:void(0)" class="cursor-default"><i class="fa fa-check text-success"></i> @lang('home.linked_to_enterprise') <span class="font-weight-bold">{{ $empresa->nome }}</span> </a></li>
                                @endforeach                               
                            @else
                                @foreach (Helper::getUserGroups() as $grupo)
                                    <li><a href="javascript:void(0)" class="cursor-default"><i class="fa fa-check text-success"></i> @lang('home.linked_to_group') <span class="font-weight-bold">{{ $grupo->nome }}</span> </a></li>
                                @endforeach                             
                            @endif
                            
                        </ul>
                    </blockquote>
                </div>
            </div>
            

        </div>
    </div>
</div>
    
@endsection
