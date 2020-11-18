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
                    <h3 class="text-themecolor m-b-0 m-t-0">Documentação</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('documentacao') }}">Documentação</a></li>
                        <li class="breadcrumb-item active">Listas de Presença</li>
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

                            <h3 class="card-title"> Listas de presença do documento: <span class="text-muted">{{ $documentName }}</span> </h3>

                            @if(Session::has('message'))
                                <div class="alert alert-{{str_before(Session::get('style'), '|')}}"> <i class="mdi mdi-{{str_after(Session::get('style'), '|')}}"></i> {{ Session::get('message') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                </div>
                            @endif

                            <div class="table-responsive m-t-40">
                                <table id="tabela-listas-presenca" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th class="text-center text-nowrap noExport">Ações</th>
                                            <th class="text-center">Nome</th>
                                            <th class="text-center">Anexada durante</th>
                                            <th class="text-center">Destinatários</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($presenceLists as $presenceList)
                                            <tr>
                                                <td class="text-center">
                                                    <a href="{{ asset('plugins/onlyoffice-php/Storage/lists') .'/'. $presenceList->nome .'.'. $presenceList->extensao }}" target="_blank" class="btn btn-success btn-sm"><i class="fa fa-eye"></i> &nbsp;Visualizar Lista</a>
                                                </td>
                                                <td class="text-center">{{ $presenceList->nome }}</td>
                                                <td class="text-center">Revisão: <span class="font-weight-bold">{{ $presenceList->revisao_documento }}</span></td>
                                                <td class="text-center">
                                                    <ul class="list-icons">
                                                        <?php echo(\App\Classes\Helpers::listEmailAddresses($presenceList->destinatarios_email)); ?>
                                                    </ul>
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

    @include('componentes._script_datatables', ['tableId' => 'tabela-listas-presenca'])

@endsection