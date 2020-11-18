@extends('core::layouts.app')

@extends('core::layouts.menuDocs')
@yield('menu')

@section('content')

<link rel="stylesheet" href="{{ asset('plugins/dropify/dist/css/dropify.min.css') }}">


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
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('documentos-externos') }}">Documentos Externos</a>
                    </li>
                    <li class="breadcrumb-item active">Acessar</li>
                </ol>
            </div>
            <div class="col-md-7 col-4 align-self-center">
                <div class="">
                    <button
                        class="right-side-toggle waves-light btn-success btn btn-circle btn-xl pull-right m-l-10  btn-badge badge-top-right"
                        data-count="{{ count(\App\Classes\Helpers::instance()->getNotifications( Auth::user()->id )) }}">
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
                        <div class="alert alert-{{str_before(Session::get('style'), '|')}}"> <i
                                class="mdi mdi-{{str_after(Session::get('style'), '|')}}"></i>
                            {{ Session::get('message') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span
                                    aria-hidden="true">&times;</span> </button>
                        </div>
                        @endif

                        <div class="row mb-4">
                            <div class="col-md-12">
                                <h3 class="card-title"> Substituição de arquivo - <span
                                        class="font-weight-bold">{{ $filename }}</span> </h3>
                            </div>
                        </div>

                        {!! Form::open(['route' => 'documentos-externos.update', 'method' => 'POST', 'enctype' =>
                        'multipart/form-data']) !!}
                        {{ csrf_field() }}

                        {!! Form::hidden('db_document_id', $dbDocument->id) !!}
                        {!! Form::hidden('document_id', $document->id) !!}
                        {!! Form::hidden('register_id', $document->idRegistro) !!}
                        {!! Form::hidden('area_id', $document->idArea) !!}

                        <div class="row">
                            <div class="col-md-12">
                                <label for="document_updated">Por favor, anexe a nova versão do arquivo, caso deseje
                                    atualizá-lo.</label>
                                {!! Form::file('document_updated', ['class' => 'dropify', 'id' => 'document_updated',
                                'required' => 'required', 'data-allowed-file-extensions'=>'pdf']) !!}
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <button type="button" id="btn-remove-document" class="btn btn-danger btn-block"
                                    data-id="{{ $document->id }}">Excluir Documento</button>
                            </div>
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-success btn-block">Atualizar Documento</button>
                            </div>
                        </div>
                        {!! Form::close() !!}

                        <div class="row mt-12">


                            <div class="col-md-12">
                                <div class="form-group mt-4">
                                    <div class="col-md-12 float-right">
                                        {!! Form::open(['route' => 'documentos-externos.approval', 'method' => 'POST']) !!}
                                            {{ csrf_field() }}
                                            
                                            {!! Form::hidden('document_id', $document->id) !!}
                                            {!! Form::hidden('db_document_id', $dbDocument->id) !!}

                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <div class="col-md-10 control-label font-bold">
                                                                {!! Form::label('sector', 'Setor:') !!}
                                                            </div>
                                                            <div class="col-md-12">
                                                                {!! Form::text('setor', $dbDocument->setor->nome, ['class' => "form-control", 'readonly'] ) !!}
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <div class="col-md-10 control-label font-bold">
                                                                {!! Form::label('fornecedor', 'Fornecedor:') !!}
                                                            </div>
                                                            <div class="col-md-12">
                                                                {!! Form::text('fornecedor', $dbDocument->fornecedor->nome ?? "Sem fornecedor", ['class' => "form-control", "readonly"] ) !!}
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <div class="col-md-10 control-label font-bold">
                                                                {!! Form::label('revisao', 'Revisão:') !!}
                                                            </div>
                                                            <div class="col-md-12">
                                                                {!! Form::number('revisao', $dbDocument->revisao, ['class' => "form-control"] ) !!}
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <div class="col-md-10 control-label font-bold">
                                                                {!! Form::label('validade', 'Data de Revisão:') !!}
                                                            </div>
                                                            <div class="col-md-12">
                                                                {!! Form::date('validade', $dbDocument->validade, ['class' => "form-control"]
                                                                ) !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 row">
                                                <div class="col-md-6">
                                                    <div class="col-md-12">
                                                        <div class="card text-center">
                                                            <div class="card-footer text-muted">
                                                                Usuários que validaram/realizaram upload do documento
                                                            </div>
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <p class="pull-left"><b>VALIDOU: </b>{{ $approver }} </p>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <p class="pull-left"><b>UPLOAD: </b>{{ $responsibleUpload }} </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mt-4">
                                                    @if ($validated)
                                                    <h4 class="text-center text-success">Esse documento já foi validado!
                                                    </h4>
                                                    @else
                                                    <input type="checkbox" id="i_approve" name="i_approve" class="filled-in" />
                                                    <label for="i_approve">Eu li e defino esse documento como <span class="font-weight-bold">validado</span>.</label>
                                                    @endif

                                                    <button type="submit" class="btn btn-block btn-success mt-1">Salvar</button>
                                                </div>
                                            </div>
                                        {!! Form::close() !!}
                                    </div>
                                </div>
                            </div>
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

{{-- SweetAlert - Exclusão --}}
<script>
    $('#btn-remove-document').click(function(){
            let documentId = $(this).data('id');
            let obj = {'document_id': documentId};

            swal({   
                title: "Você tem certeza?",   
                text: "Esse arquivo será deletado!",   
                type: "warning",   
                showCancelButton: true,   
                confirmButtonColor: "#DD6B55",   
                confirmButtonText: "Sim, eu tenho certeza!",   
                cancelButtonColor: "#DD6B55",   
                cancelButtonText: "Cancelar",   
                closeOnConfirm: false 
            }, function(){

                ajaxMethod('DELETE', " {{ URL::route('documentos-externos.delete') }} ", obj).then(function(result) {  
                    if(result.response === 'success') {
                        window.location.href = "{{ URL::to('documentos-externos' )}}"
                    } else {
                        swalWithReload("Ops!", "Tivemos um problema ao remover o registro. Por favor, contate o suporte!.", "error");
                    }
                }, function(err) {
                });
            });
        });
</script>


{{-- jQuery File Upload --}}
<script>
    $('.dropify').dropify({
            messages: {
                default: 'Arraste um arquivo para cá ou clique',
                replace: 'Arraste um arquivo para cá ou clique para substituir',
                remove: 'Remover',
                error: 'Erro ao processar arquivo, contate o suporte técnico (suporte@speedsoftware.com.br)'
            },
            error:{
                fileExtension:'O formato do arquivo não é suportado (pdf apenas).'
            } 
        });
</script>

@endsection