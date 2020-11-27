@extends('layouts.app')

@extends('layouts.menuDocs')
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
                        <li class="breadcrumb-item">Controle de Registros</li>
                        <li class="breadcrumb-item active">Cadastro</li>
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

                                {{ Session::forget('message') }}
                            @endif

                            {!! Form::open(['route' => 'controle-registros.filter-options', 'class' => 'form-horizontal']) !!}
                                <div class="row">
                                    <div class="col-md-4">
                                        <label>Campo</label>
                                        {!! Form::select('campo', Constants::$CONTROLE_REGISTROS, null, ['class' => 'form-control mt-1 custom-select']) !!}
                                    </div>
                                    <div class="col-md-3 m-t-30">
                                        <button type="submit" class="btn btn-block waves-effect waves-light btn-outline-success"><i class="fa fa-search"></i> Filtrar</button>
                                    </div>
                                    <div class="col-md-1"></div>
                                    <div class="col-md-4 m-t-30">
                                        <a href="{{ route('controle-registros.create-option') }}" class="btn btn-block waves-effect waves-light btn-outline-info"><i class="fa fa-plus"></i> Criar Opção</a>
                                    </div>
                                </div>
                            {!! Form::close() !!}

                            <div class="table-responsive m-t-40">
                                <table id="tabela-opcoes-controle-registros" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Descrição</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center text-nowrap noExport">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($options as $option)
                                            <tr>
                                                <td class="text-center">{{ $option->descricao }}</td>
                                                <td class="text-center">
                                                    @if( $option->ativo )
                                                        <span class='text-success'>Ativo</span>
                                                    @else
                                                        <span class='text-danger'>Inativo</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <button class="btn btn-danger btn-sm sa-warning" data-id="{{ $option->id }}"><i class="fa fa-trash"></i> &nbsp;Deletar</button>
                                                    <a href="{{ route('controle-registros.edit-option', ['option' => $option->id]) }}" class="btn btn-info btn-sm"><i class="fa fa-pencil"></i> &nbsp;Editar</a>
                                                </td>
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

    @include('componentes._script_datatables', ['tableId' => 'tabela-opcoes-controle-registros'])
    @include('componentes._script_sweetalert', ['route' => 'controle-registros.delete-option'])

@endsection