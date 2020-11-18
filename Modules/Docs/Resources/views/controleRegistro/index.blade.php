@extends('core::layouts.app')

@extends('core::layouts.menuDocs')
@yield('menu')

@section('content')
    
    <!-- ============================================================== -->
    <!-- Page wrapper  -->
    <!-- ============================================================== -->
    <div class="page-wrapper">
        <div class="container-fluid">
            

            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <div class="row page-titles">
                <div class="col-md-5 col-8 align-self-center">
                    <h3 class="text-themecolor m-b-0 m-t-0">Controle de Registros</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ URL::route('home') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Controle de Registros</li>
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
            <!-- ============================================================== -->
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            
            
            <!-- ============================================================== -->
            <!-- Start Page Content -->
            <!-- ============================================================== -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            @if(Session::has('message'))
                                <div class="alert alert-{{str_before(Session::get('style'), '|')}}"> <i class="mdi mdi-{{str_after(Session::get('style'), '|')}}"></i> {{ Session::get('message') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                </div>
                            @endif

                            <div class="col-md-12">
                                <a href="{{ route('controle-registros.create') }}" class="btn waves-effect waves-light btn-lg btn-success pull-right mb-4">Criar Registro </a>
                            </div>

                            <div class="table-responsive m-t-40">
                                <table id="tabela-controle-registros" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th class="text-center text-nowrap noExport">Ações</th>
                                            <th class="text-center">Código</th>
                                            <th class="text-center">Título</th>
                                            <th class="text-center">Responsável</th>
                                            <th class="text-center">Meio</th>
                                            <th class="text-center">Armazenamento</th>
                                            <th class="text-center">Proteção</th>
                                            <th class="text-center">Recuperação</th>
                                            <th class="text-center">Acesso</th>
                                            <th class="text-center">Retenção - Local</th>
                                            <th class="text-center">Retenção - Arquivo Morto</th>
                                            <th class="text-center">Disposição</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($registros as $registro)
                                            <tr>
                                                <td class="text-center">
                                                    @if ($registro->avulso)
                                                        <a href="{{ route('controle-registros.edit', ['registro' => $registro->id]) }}" class="mr-3"> <i class="fa fa-pencil text-info" data-toggle="tooltip" data-original-title="Editar Informações"></i> </a>
                                                    @endif

                                                    <a href="#" class="sa-warning" data-id="{{ $registro->id }}"> <i class="fa fa-trash text-danger" data-toggle="tooltip" data-original-title="Excluir"></i> </a>
                                                </td>
                                                <td class="text-center">{{ $registro->codigo }}</td>
                                                <td class="text-center">{{ $registro->titulo }}</td>
                                                <td class="text-center">{{ $registro->setor->nome }}</td>
                                                <td class="text-center">{{ \App\Classes\Helpers::getDescription($registro->meio_distribuicao_id) }}</td>
                                                <td class="text-center">{{ \App\Classes\Helpers::getDescription($registro->local_armazenamento_id) }}</td>
                                                <td class="text-center">{{ \App\Classes\Helpers::getDescription($registro->protecao_id) }}</td>
                                                <td class="text-center">{{ \App\Classes\Helpers::getDescription($registro->recuperacao_id) }}</td>
                                                <td class="text-center">{{ $registro->nivel_acesso }}</td>
                                                <td class="text-center">{{ \App\Classes\Helpers::getDescription($registro->tempo_retencao_local_id) }}</td>
                                                <td class="text-center">{{ \App\Classes\Helpers::getDescription($registro->tempo_retencao_deposito_id) }}</td>
                                                <td class="text-center">{{ \App\Classes\Helpers::getDescription($registro->disposicao_id) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>      
                            </div>

                            
                        </div>
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- End Page Content -->
            <!-- ============================================================== -->
            

        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End Page wrapper -->
    <!-- ============================================================== -->

@endsection


@section('footer')

    @include('componentes._script_datatables', ['tableId' => 'tabela-controle-registros'])
    @include('componentes._script_sweetalert', ['route' => 'controle-registros.delete'])

@endsection