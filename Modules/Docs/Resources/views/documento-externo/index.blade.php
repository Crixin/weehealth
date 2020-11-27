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
                    <h3 class="text-themecolor m-b-0 m-t-0">Documentos Externos</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ URL::route('home') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Documentos Externos</li>
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
                            @elseif(Session::has('removed-doc-success'))
                                <div class="alert alert-success"> <i class="mdi mdi-check-circle"></i> {{ Session::get('removed-doc-success') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                </div>

                                @php
                                    Session::forget('removed-doc-success');
                                @endphp
                            @endif

                            <div class="row">
                                <div class="col-md-12">
                                    <form action="{{ route('documentos-externos.store') }}" class="dropzone" id="my-dropzone" method="POST" enctype="multipart/form-data">
                                        {{ csrf_field() }}
                                        
                                        <div class="fallback">
                                            <input name="file" type="file" multiple required />
                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-12">
                                    @if ($nonCreatedArea)
                                        <h3 class="text-center mt-5">Não existe área criada para o seu setor.</h3> 
                                        <h5>Por favor, solicite ao suporte técnico para que ela seja criada!</h5>
                                    @else
                                        <div class="row">

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <div class="col-md-10 control-label font-bold">
                                                        {!! Form::label('sector', 'Setor:') !!}
                                                    </div>
                                                    <div class="col-md-12">
                                                        <select class="form-control selectpicker" data-size="10" data-live-search="true" data-actions-box="true" name="sector" id="id_area_sector" required>
                                                            @foreach($areasBySector as $idArea => $nome)
                                                                <option value="{{$idArea}}">{{$nome}} </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <div class="col-md-10 control-label font-bold">
                                                        {!! Form::label('fornecedor', 'Fornecedor:') !!}
                                                    </div>
                                                    <div class="col-md-12">
                                                        <select class="form-control selectpicker" data-size="10" data-live-search="true" data-actions-box="true" name="fornecedor" id="fornecedor" required>
                                                            <option value="">Nenhum</option>
                                                            @foreach($fornecedores as $key => $fornecedor)
                                                                <option value="{{$fornecedor->id}}">{{$fornecedor->nome}} </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <div class="col-md-10 control-label font-bold">
                                                        {!! Form::label('revisao', 'Revisão:') !!}
                                                    </div>
                                                    <div class="col-md-12">
                                                        {!! Form::number('revisao', null, ['class' => "form-control"] ) !!}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <div class="col-md-10 control-label font-bold">
                                                        {!! Form::label('validade', 'Data de Revisão:') !!}
                                                    </div>
                                                    <div class="col-md-12">
                                                        {!! Form::date('validade', null, ['class' => "form-control"] ) !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-5 mt-1 float-right">
                                                <input type="checkbox" id="i_approve" name="i_approve" class="filled-in" />
                                                <label for="i_approve">Eu li e defino esse(s) documento(s) como <span class="font-weight-bold">validado(s)</span>.</label>

                                                <button class="btn btn-block btn-success mt-4" id="btn-upload-docs">Salvar</button>
                                                <a href="{{ route('documentos-externos') }}" class="btn btn-block btn-secondary">Limpar</a>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <hr style="border: 1px solid gray;">
{{--                             <div class="dt-buttons">
                                <a class="dt-button buttons-excel buttons-html5" tabindex="0" aria-controls="DataTables_Table_0" href="#"> <span>Excel</span> </a>
                                <a class="dt-button buttons-pdf buttons-html5" tabindex="0" aria-controls="DataTables_Table_0" href="#"><span>PDF</span></a>
                                <a class="dt-button buttons-print" tabindex="0" aria-controls="DataTables_Table_0" href="#"><span>Imprimir</span></a>
                            </div> --}}
                            <table class="nowrap table table-hover table-striped table-bordered table-todos-documentos-externos" cellspacing="0" width="100%">
                                <thead>
                                    <tr style="display:none;">
                                        <td class="nowrap">Setor</td>
                                        <td class="nowrap">Fornecedor</td>
                                        <td class="nowrap">Título do Documento</td>
                                        <td class="nowrap">Data de Criação</td>
                                        <td class="nowrap">Data de Revisão</td>
                                   </tr>    
                                </thead>
                                <tbody> 
                                    @foreach ($registers as $key => $setor)
                                        @foreach ($setor as $keyFilho => $valorSetor)
                                            @if ($keyFilho == "SemFornecedor")
                                                @foreach ($setor['SemFornecedor']['files'] as $file)
                                                    <tr style="display:none;">
                                                        <td class="text-center text-nowrap">{{$key}}</td>
                                                        <td class="text-center text-nowrap">Sem Fornecedor</td>
                                                        <td class="nowrap"> {{ $file->endereco}}</td>
                                                        <td class="nowrap"> {{ $file->listaIndice[2]->valor}}</td>
                                                        <td class="nowrap"> {{ $setor['SemFornecedor'][$file->id]['validade'] ?? ""}}</td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                @foreach ($valorSetor['files'] as $file)
                                                    <tr style="display:none;">
                                                        <td class="text-center text-nowrap">{{$key}}</td>
                                                        <td class="text-center text-nowrap">{{$valorSetor['dados_fornecedor']->nome}}</td>
                                                        <td class="nowrap"> {{ $file->endereco}}</td>
                                                        <td class="nowrap"> {{ $file->listaIndice[2]->valor}}</td>
                                                        <td class="nowrap"> {{ $valorSetor[$file->id]['validade'] ?? ""}}</td>
                                                    </tr>
                                                @endforeach
                                            @endif    
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                            <div id="sectorsList" class="accordion" role="tablist" aria-multiselectable="true">

                                @forelse ($registers as $key => $setor)
                                    <div class="card">
                                        <div class="card-header cursor-pointer-on" role="tab" id="headingOne" data-toggle="collapse" data-parent="#sectorsList" href="#{{ str_replace(' ', '', $key) }}" aria-expanded="true" aria-controls="{{ str_replace(' ', '', $key) }}">
                                             <h4 class="mb-0 text-info">{{ $key }}</h4>
                                             <!-- Sorry HSUAHSUAHSAUSHAUHH não achei outra forma mais izi-->
                                            <table class="nowrap table table-hover table-striped table-bordered table-setor-documentos-externos" cellspacing="0" width="100%">
                                                <thead> 
                                                     <tr style="display:none;">
                                                         <td class="nowrap">Fornecedor</td>
                                                         <td class="nowrap">Título do Documento</td>
                                                         <td class="nowrap">Data de Criação</td>
                                                         <td class="nowrap">Data de Revisão</td>
                                                    </tr>    
                                                </thead>
                                                <tbody>
                                                    @foreach ($setor as $keyFilho => $valorSetor)
                                                        @if ($keyFilho == "SemFornecedor")
                                                            @foreach ($setor['SemFornecedor']['files'] as $file)
                                                                <tr style="display:none;">
                                                                    <td class="text-center text-nowrap">Sem Fornecedor</td>
                                                                    <td class="nowrap"> {{ $file->endereco}}</td>
                                                                    <td class="nowrap"> {{ $file->listaIndice[2]->valor}}</td>
                                                                    <td class="nowrap"> {{ $setor['SemFornecedor'][$file->id]['validade'] ?? ""}}</td>
                                                                </tr>
                                                            @endforeach
                                                        @else
                                                            @foreach ($valorSetor['files'] as $file)
                                                                <tr style="display:none;">
                                                                    <td class="text-center text-nowrap">{{$valorSetor['dados_fornecedor']->nome}}</td>
                                                                    <td class="nowrap"> {{ $file->endereco}}</td>
                                                                    <td class="nowrap"> {{ $file->listaIndice[2]->valor}}</td>
                                                                    <td class="nowrap"> {{ $valorSetor[$file->id]['validade'] ?? ""}}</td>
                                                                </tr>
                                                            @endforeach
                                                        @endif    
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        
                                        <div id="{{ str_replace(' ', '', $key) }}" class="collapse" role="tabpanel" aria-labelledby="headingOne">

                                        @foreach ($setor as $keyFilho => $valorSetor)

                                            <div class="card-header cursor-pointer-on ml-4" role="tab" id="headingOne{{str_replace(' ', '', $key) . str_replace(' ', '', $keyFilho)}}" data-toggle="collapse" data-parent="#sectorsList2" href="#{{str_replace(' ', '', $key) . str_replace(' ', '', $keyFilho)}}" aria-expanded="true" aria-controls="{{str_replace(' ', '', $key) . str_replace(' ', '', $keyFilho)}}">
                                                <h4 class="mb-0 text-info"> {{$keyFilho == "SemFornecedor" ? "Sem fornecedor" : $valorSetor['dados_fornecedor']->nome}}</h4>
                                            </div>
                                                    
                                            <div id="{{str_replace(' ', '', $key) . str_replace(' ', '', $keyFilho)}}" class="collapse ml-4" role="tabpanel" aria-labelledby="headingOne{{str_replace(' ', '', $key) . str_replace(' ', '', $keyFilho)}}">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-12">

                                                            <table class="nowrap table table-hover table-striped table-bordered table-documentos-externos" cellspacing="0" width="100%">
                                                                <thead>
                                                                    <tr>
                                                                        <td class="nowrap">Ações</td>
                                                                        <td class="nowrap">Título do Documento</td>
                                                                        <td class="nowrap">Data de Criação</td>
                                                                        <td class="nowrap">Data de Revisão</td>
                                                                    </tr>
                                                                </thead>
                                                                
                                                                <tbody>
                                                                    
                                                                    @if ($keyFilho == "SemFornecedor")
                                                                        @foreach ($setor['SemFornecedor']['files'] as $file)
                                                                            <tr>
                                                                                <td class="text-center text-nowrap">
                                                                                    <a href="{{ route('documentos-externos.bytes', ["document_id" => $file->id, "nome" => $file->endereco]) }}" target="_blank" ><i class="fa fa-file-text-o text-primary mr-3" data-id="{{$file->id}}" data-toggle="tooltip" data-original-title="Visualizar Arquivo"></i></a>
                                                                                    <a href="{{ url('/documentos-externos/acessar-documento/' . $file->id) }}" class="mr-3" ><i class="fa fa-pencil text-success" data-toggle="tooltip" data-original-title="Editar Informações"></i></a>
                                                                                </td>
                                                                                <td class="nowrap"> {{ $file->endereco}}</td>
                                                                                <td class="nowrap"> {{ $file->listaIndice[2]->valor}}</td>
                                                                                <td class="nowrap"> {{ $setor['SemFornecedor'][$file->id]['validade'] ?? ""}}</td>
                                                                            </tr>
                                                                        @endforeach
                                                                    @else
                                                                        @foreach ($valorSetor['files'] as $file)
                                                                            <tr>        
                                                                                <td class="text-center text-nowrap">
                                                                                    <a href="{{ route('documentos-externos.bytes', ["document_id" => $file->id, "nome" => $file->endereco]) }}" target="_blank" ><i class="fa fa-file-text-o text-primary mr-3" data-id="{{$file->id}}" data-toggle="tooltip" data-original-title="Visualizar Arquivo"></i></a>
                                                                                    <a href="{{ url('/documentos-externos/acessar-documento/' . $file->id) }}" class="mr-3" ><i class="fa fa-pencil text-success" data-toggle="tooltip" data-original-title="Editar Informações"></i></a>
                                                                                </td>
                                                                                <td class="nowrap"> {{ $file->endereco}}</td>
                                                                                <td class="nowrap"> {{ $file->listaIndice[2]->valor}}</td>
                                                                                <td class="nowrap"> {{ $valorSetor[$file->id]['validade'] ?? ""}}</td>
                                                                            </tr>
                                                                        @endforeach
                                                                    @endif
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @empty
                                    <h5 class="text-center">Não existe nenhum pendente de revisão ou revisado!</h5>
                                @endforelse
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
    
    {{-- Dropzone Plugin JavaScript --}}
    <link href="{{ asset('plugins/dropzone-master/dist/dropzone.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('plugins/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet" type="text/css" />
    
    <script src="{{ asset('plugins/dropzone-master/dist/dropzone.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap-select/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/dataTables/dataTables-1.2.2.buttons.min.js') }}"></script>
    <script src="{{ asset('js/dataTables/buttons-1.2.2.flash.min.js') }}"></script>
    <script src="{{ asset('js/dataTables/jszip-2.5.0.min.js') }}"></script>
    <script src="{{ asset('js/dataTables/pdfmake-0.1.18.min.js') }}"></script>
    <script src="{{ asset('js/dataTables/vfs_fonts-0.1.18.js') }}"></script>
    <script src="{{ asset('js/dataTables/buttons-1.2.2.html5.min.js') }}"></script>
    <script src="{{ asset('js/dataTables/buttons-1.2.2.print.min.js') }}"></script>

    <script>

        // https://www.dropzonejs.com/#configuration
        Dropzone.autoDiscover = false;

        var myDropzone = new Dropzone("#my-dropzone", {
            // paramName: "docs-externos", // The name that will be used to transfer the file
            maxFilesize: 10, // MB
            maxFiles: 15,
            parallelUploads: 15,
            uploadMultiple: true,
            acceptedFiles: 'application/pdf',
            timeout: 10000,
            autoProcessQueue: false,
            dictDefaultMessage: 'Adicione ou arraste os arquivos para cá',
            dictInvalidFileType: 'Somente arquivos .pdf são permitidos',
            dictMaxFilesExceeded: 'Você só pode subir 15 documentos por vez.',
            init: function() {
                this.on("sending", function(file, xhr, formData) {
                    formData.append("id_area_sector", $("#id_area_sector").val());
                    formData.append("fornecedor", $("#fornecedor").val());
                    formData.append("validade", $("#validade").val());
                    formData.append("revisao", $("#revisao").val());
                    formData.append("sector_name", $("#id_area_sector option:selected").text());
                    formData.append("i_approve", document.getElementById("i_approve").checked);
                });

                this.on("success", function(file, response) {
                    if( 'success' in response ) {
                        swalWithReload("Sucesso!", response.success, "success");
                    } else {
                        swalWithReload("Ops!", response.error, "error");
                    }
                });
            }
        });

        $("#btn-upload-docs").on('click', function() {
            myDropzone.processQueue();
        });

        $('.table-documentos-externos').DataTable({
            "language": {
                "sEmptyTable": "Nenhum registro encontrado",
                "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
                "sInfoFiltered": "(Filtrados de _MAX_ registros)",
                "sInfoPostFix": "",
                "sInfoThousands": ".",
                "sLengthMenu": "_MENU_ resultados por página",
                "sLoadingRecords": "Carregando...",
                "sProcessing": "Processando...",
                "sZeroRecords": "Nenhum registro encontrado",
                "sSearch": "Pesquisar",
                "oPaginate": {
                    "sNext": "Próximo",
                    "sPrevious": "Anterior",
                    "sFirst": "Primeiro",
                    "sLast": "Último"
                },
                "oAria": {
                    "sSortAscending": ": Ordenar colunas de forma ascendente",
                    "sSortDescending": ": Ordenar colunas de forma descendente"
                }
            },
            dom: 'Bfrtip',
            buttons: [
                { 
                    extend: 'excel',  
                    text: 'Excel',
                    title: "Controle de Documentos Externos",
                    exportOptions: {
                        columns: [ 1, 2, 3 ]
                    }  
                },
                { 
                    extend: 'pdf',
                    text: 'PDF',
                    title: "Controle de Documentos Externos",
                    exportOptions: {
                        columns: [ 1, 2, 3 ]
                    } 
                },
                { 
                    extend: 'print',  
                    text: 'Imprimir',
                    title: "Controle de Documentos Externos",
                    exportOptions: {
                        columns: [ 1, 2, 3 ]
                    } 
                }
            ]
        });

        $('.table-todos-documentos-externos').DataTable({
            "language": {
                "sEmptyTable": "Nenhum registro encontrado",
                "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
                "sInfoFiltered": "(Filtrados de _MAX_ registros)",
                "sInfoPostFix": "",
                "sInfoThousands": ".",
                "sLengthMenu": "_MENU_ resultados por página",
                "sLoadingRecords": "Carregando...",
                "sProcessing": "Processando...",
                "sZeroRecords": "Nenhum registro encontrado",
                "sSearch": "Pesquisar",
                "oPaginate": {
                    "sNext": "Próximo",
                    "sPrevious": "Anterior",
                    "sFirst": "Primeiro",
                    "sLast": "Último"
                },
                "oAria": {
                    "sSortAscending": ": Ordenar colunas de forma ascendente",
                    "sSortDescending": ": Ordenar colunas de forma descendente"
                }
            },
            dom: 'Brt',
            buttons: [
                { 
                    extend: 'excel',  
                    text: 'Excel',
                    title: "Controle de Documentos Externos",
                },
                { 
                    extend: 'pdf',
                    text: 'PDF',
                    title: "Controle de Documentos Externos",
                },
                { 
                    extend: 'print',  
                    text: 'Imprimir',
                    title: "Controle de Documentos Externos",
                }
            ]
        });
        
        $('.table-setor-documentos-externos').DataTable({
            "language": {
                "sEmptyTable": "Nenhum registro encontrado",
                "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
                "sInfoFiltered": "(Filtrados de _MAX_ registros)",
                "sInfoPostFix": "",
                "sInfoThousands": ".",
                "sLengthMenu": "_MENU_ resultados por página",
                "sLoadingRecords": "Carregando...",
                "sProcessing": "Processando...",
                "sZeroRecords": "Nenhum registro encontrado",
                "sSearch": "Pesquisar",
                "oPaginate": {
                    "sNext": "Próximo",
                    "sPrevious": "Anterior",
                    "sFirst": "Primeiro",
                    "sLast": "Último"
                },
                "oAria": {
                    "sSortAscending": ": Ordenar colunas de forma ascendente",
                    "sSortDescending": ": Ordenar colunas de forma descendente"
                }
            },
            dom: 'Brt',
            buttons: [
                { 
                    extend: 'excel',  
                    text: 'Excel',
                    title: "Controle de Documentos Externos",
                },
                { 
                    extend: 'pdf',
                    text: 'PDF',
                    title: "Controle de Documentos Externos",
                },
                { 
                    extend: 'print',  
                    text: 'Imprimir',
                    title: "Controle de Documentos Externos",
                }
            ]
        });








    </script>

    <!-- jQuery Loading Plugin -->
    <link href="{{ asset('plugins/jquery-loading/jquery.loading.min.css') }}" rel="stylesheet">
    <script src="{{ asset('plugins/jquery-loading/jquery.loading.min.js') }}"></script>

@endsection