@extends('layouts.app')

@section('page_title', __('page_titles.docs.documento.presence-list'))

@section('breadcrumbs')

    <li class="breadcrumb-item"><a href="{{ route('core.home') }}"> @lang('page_titles.general.home') </a></li>
    <li class="breadcrumb-item"><a href="{{ route('docs.documento') }}"> @lang('page_titles.docs.documento.index') </a></li>
    <li class="breadcrumb-item active"> @lang('page_titles.docs.documento.presence-list') </li>    

@endsection

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-body">
            <!-- ============================================================== -->
            <!-- Start Page Content -->
            <!-- ============================================================== -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <h3 class="card-title"> Listas de presença do documento: <span class="text-muted">{{ $documento->nome }}</span> </h3>

                            @if(Session::has('message'))
                                <div class="alert alert-{{str_before(Session::get('style'), '|')}}"> <i class="mdi mdi-{{str_after(Session::get('style'), '|')}}"></i> {{ Session::get('message') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                </div>
                            @endif

                            <div class="table-responsive m-t-40">
                                <table id="tabela-listas-presenca" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Nome</th>
                                            <th class="text-center">Anexada durante</th>
                                            <th class="text-center">Destinatários</th>
                                            <th class="text-center text-nowrap noExport">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($listaPresenca as $lista)
                                            <tr>
                                                
                                                <td class="text-center">{{ $lista->nome }}</td>
                                                <td class="text-center">Revisão: <span class="font-weight-bold">{{ $lista->revisao_documento }}</span></td>
                                                <td class="text-center">
                                                    <ul class="list-icons">
                                                        <?php echo(\App\Classes\Helper::listEmailAddresses($lista->destinatarios_email)); ?>
                                                    </ul>
                                                </td>
                                                <td class="text-center">
                                                  <a href="#" data-id="{{$lista->id}}" class="btn btn-success btn-sm btn-view-lista"><i class="fa fa-eye"></i> &nbsp;Visualizar Lista</a>
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
</div>
@endsection


@section('footer')

    <!-- *** DataTable *** -->
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>

<!-- start - This is for export functionality only -->
<script src="{{ asset('js/dataTables/dataTables-1.2.2.buttons.min.js') }}"></script>
<script src="{{ asset('js/dataTables/buttons-1.2.2.flash.min.js') }}"></script>
<script src="{{ asset('js/dataTables/jszip-2.5.0.min.js') }}"></script>
<script src="{{ asset('js/dataTables/pdfmake-0.1.18.min.js') }}"></script>
<script src="{{ asset('js/dataTables/vfs_fonts-0.1.18.js') }}"></script>
<script src="{{ asset('js/dataTables/buttons-1.2.2.html5.min.js') }}"></script>
<script src="{{ asset('js/dataTables/buttons-1.2.2.print.min.js') }}"></script>
<!-- end - This is for export functionality only -->

<link href="{{ asset('plugins/jquery-loading/jquery.loading.min.css') }}" rel="stylesheet">
<script src="{{ asset('plugins/jquery-loading/jquery.loading.min.js') }}"></script>

<script>
  let reportTitle = "{{ $documento->nome ?? env('APP_NAME')}}";

  $(document).ready(function() {
    $("#tabela-listas-presenca").DataTable({
      "pageLength": 30,
      "language": {
        "sEmptyTable": "Nenhum registro encontrado",
        "sInfo": "Exibindo de _START_ a _END_ de _TOTAL_ registros",
        "sInfoEmpty": "Nenhum registro encontrado",
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
          title: reportTitle,
          exportOptions: {
            columns: "thead th:not(.noExport)"
          }  
        },
        { 
          extend: 'pdf',
          text: 'PDF',
          title: reportTitle,
          exportOptions: {
            // columns: [ 0, 1]
            columns: "thead th:not(.noExport)"
          } 
        },
        { 
          extend: 'print',  
          text: 'Imprimir',
          title: reportTitle,
          exportOptions: {
            columns: "thead th:not(.noExport)"
          } 
        }
      ]
    });


    $('.btn-view-lista').on('click', function(){
      $("body").loading(
        {
          stoppable: true,
          message: "Carregando...",
          theme: "dark"
        }
      );
      let id = $(this).data('id');
      let obj = {'id': id};
      ajaxMethod('POST', "{{ URL::route('docs.lista-presenca.busca-lista-ged') }}", obj).then(ret => {
          if(ret.response == 'erro') {
              swal2_alert_error_support("Tivemos um problema ao buscar a lista de presença no GED.");
          }
          window.open(ret.data.caminho, '_blank');
          $("body").loading('stop');
      }, error => {
          console.log(error);
          $("body").loading('stop');
      });
    });

  });
</script>
@endsection