@extends('core::layouts.app')

@extends('core::layouts.menuDocs')
@yield('menu')

@section('content')

    <div class="page-wrapper">
        <div class="container-fluid">
            

            <div class="row page-titles">
                <div class="col-md-5 col-8 align-self-center">
                    <h3 class="text-themecolor m-b-0 m-t-0">Controle de Registros</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('controle-registros') }}">Controle de Registros</a></li>
                        <li class="breadcrumb-item active">Editar Registro</li>
                    </ol>
                </div>
                <div class="col-md-7 col-4 align-self-center">
                    <div class="">
                        <button class="right-side-toggle waves-light btn-success btn btn-circle btn-xl pull-right m-l-10  btn-badge badge-top-right" data-count="{{ count(\App\Classes\Helpers::instance()->getNotifications( Auth::user()->id )) }}">
                            <i class="ti-comment-alt text-white"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <!-- Alertas - Validação do Form -->
                            @if(Session::has('message'))
                                <div class="alert alert-{{str_before(Session::get('style'), '|')}}"> <i class="mdi mdi-{{str_after(Session::get('style'), '|')}}"></i> {{ Session::get('message') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                </div>
                            @endif
                            
                            <div class="col-md-12">
                                {!! Form::open(['route' => 'controle-registros.update']) !!}

                                    {{ method_field('PUT') }}
                                    {!! Form::hidden('id_registro', $registro->id) !!}
                                    @include('componentes.form_controle_registros', ['registro' => $registro])

                                {!! Form::close() !!}
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
            

        </div>
    </div>

@endsection


@section('footer')
    <script>
        $(".select2-selection").css('min-height', '38px');
        $(".select2-selection__rendered").css('line-height', '38px');
    </script>
@endsection