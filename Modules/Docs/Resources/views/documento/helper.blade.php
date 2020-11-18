@extends('core::layouts.app')

@extends('core::layouts.menuDocs')
@yield('menu')

@section('content')
	<div class="page-wrapper">
        <div class="container-fluid">
            

            <div class="row page-titles">
                <div class="col-md-5 col-8 align-self-center">
                    <h3 class="text-themecolor m-b-0 m-t-0">Documentação</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ URL::route('home') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Documentação</li>
                    </ol>
                </div>
                <div class="col-md-7 col-4 align-self-center">
                    <div class="">
                        <button class="right-side-toggle waves-light btn-success btn btn-circle btn-xl pull-right m-l-10   btn-badge badge-top-right" data-count="{{ count(\App\Classes\Helpers::instance()->getNotifications( Auth::user()->id )) }}">
                            <i class="ti-comment-alt text-white"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            
            <!-- Start Page Content -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            
                            Ops! Este <i>workflow</i> ainda não está completamente pronto. Pensando nisso e com o objetivo de evitar um primeiro contato desagradável com processo, como um todo, estamos mostrando essa mensagem.
                            <br><br>
                            Assim que o processo for finalizado, atualizaremos o conteúdo desta página e as ações mostradas nos <i>MVP's</i> estarão dispostas aqui.
                            <br><br>
                            Pedimos desculpas pelo transtorno!

                        </div>
                    </div>
                </div>
            </div>
            <!-- End Page Content -->


        </div>
    </div>
@endsection