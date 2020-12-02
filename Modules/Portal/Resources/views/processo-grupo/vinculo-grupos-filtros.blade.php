@extends('layouts.app')

@extends('layouts.menuPortal')
@yield('menu')

@section('page_title', __('page_titles.portal.empresa-processo-grupo.create'))

@section('breadcrumbs')

    <li class="breadcrumb-item"><a href="{{ route('portal.home') }}"> @lang('page_titles.general.home') </a></li>
    <li class="breadcrumb-item"><a href="{{ route('portal.empresa') }}"> @lang('page_titles.portal.enterprise.index') </a></li>
    <li class="breadcrumb-item"> @lang('page_titles.portal.enterprise.linked_processes') </li>
    <li class="breadcrumb-item active"> @lang('page_titles.portal.empresa-processo-grupo.create') </li>

@endsection



@section('content')

    <div class="col-12">
        <div class="card">
            <div class="card-body">

                @if(Session::has('message'))
                    @component('components.alert')
                    @endcomponent

                    {{ Session::forget('message') }}
                @endif

                <form method="POST" action="{{ route('portal.empresa-processo-grupo.salvar') }}">
                    {{ csrf_field() }}
                    
                    <input type="hidden" name="empresaProcessoId" value="{{ $empresaProcesso->id }}">

                    <div class="form-body">
                        
                        {{-- Parte 1: cadastro --}}
                        <h3 class="box-title"> <b> {{ $empresaProcesso->coreEmpresa->nome }} <i class="mdi mdi-link"></i> {{ $empresaProcesso->portalProcesso->nome}} </b>  </h3>
                        <hr class="m-t-0 m-b-10">
                        <h3 class="box-title mt-4"> @lang('page_titles.portal.empresa-processo-grupo.available-groups') </h3>
                        <hr class="m-t-0 m-b-10">

                        @if (count($gruposNaoVinculados))
                            <div class="row p-t-20">
                                <div class="col-md-12 m-b-30">
                                    <select multiple id="grupos_a_vincular" name="grupos_a_vincular[]">
                                        @foreach ($gruposNaoVinculados as $grupo)
                                            <option value="{{ $grupo['id'] }}">{{ $grupo['nome'] }}</option>
                                        @endforeach
                                    </select>
                                    <div class="button-box m-t-20">
                                        <a id="select-all" class="btn btn-info" href="#"> @lang('buttons.general.select_all') </a> 
                                        <a id="deselect-all" class="btn btn-danger" href="#"> @lang('buttons.general.deselect_all') </a> 
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> @lang('buttons.portal.empresa-processo-grupo.create-links')</button>
                                <a href="{{ route('portal.empresa.processosVinculados', ['id' => $empresaProcesso->coreEmpresa->id]) }}" class="btn btn-inverse"> @lang('buttons.general.back')</a>
                            </div>
                        @else
                            <div class="row p-t-20 m-b-40">
                                <div class="col-md-12 text-center">
                                    <div class="alert alert-warning"> <i class="mdi mdi-alert-circle"></i> Todos os grupos já estão vinculados.
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions">
                                <a href="{{ route('portal.empresa.processosVinculados', ['id' => $empresaProcesso->coreEmpresa->id]) }}" class="btn btn-inverse"> @lang('buttons.general.back')</a>
                            </div>
                        @endif
                    </div>
                </form>
                <form method="POST" action="{{ route('portal.empresa-processo-grupo.alterar') }}" id="update-form">
                    {{ csrf_field() }}
                    
                    <input type="hidden" name="empresaProcessoId" value="{{ $empresaProcesso->id }}">
                    <input type="hidden" name="filters" id="filters">

                    <div class="form-body">
                        
                        {{-- Parte 2: listagem --}}
                        <h3 class="box-title m-t-40">  @lang('page_titles.portal.empresa-processo-grupo.linked-groups') <span style="font-weight: bold;"></span> <small> - Defina os valores dos filstros de cada um abaixo</small> </h3>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                @if (count($gruposVinculados))    
                                    <div class="table-responsive">
                                        <table id="dataTable-empresa-processo-grupo" style="white-space: nowrap;" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                            <thead class="nowrap">
                                                <tr>
                                                    <th>Grupo</th>
                                                        @foreach ($cabecalho as $descricao)
                                                            <th> {{ $descricao }} </b></th>
                                                        @endforeach
                                                    <th>Remover Vínculo</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($gruposVinculados as $infoGrupo)
                                                    <tr data-id-empresa-processo-grupo="{{ $infoGrupo->id }}">
                                                        <td><b> {{ $infoGrupo->coreGrupo->nome}} </b></td>

                                                        @foreach (json_decode($infoGrupo->portalEmpresaProcesso->indice_filtro_utilizado) as $key => $indice)
                                                        
                                                            <td class="td-{{ $infoGrupo->id . '-' . $key }}"></td>
                                                            <script>
                                                                (function() {
                                                                    setTimeout(function(){
                                                                        let config = {
                                                                            "onlyComponents": true,
                                                                            "valoresPreDefinidos": {!!$infoGrupo->filtros !!},
                                                                        }
                                                                        createFiltersComponentsGED([{!! $indice !!}] , $(".td-"+{!! $infoGrupo->id !!} + "-"+{!! $key !!}), {!! json_encode($tipoIndicesGED, JSON_HEX_TAG) !!}, config);
                                                                    }, 1000);
                                                                })();
                                                            </script>
                                                        
                                                        @endforeach
                                                        
                                                        <td> 
                                                            <button type="button" class="btn waves-effect waves-light btn-danger sa-warning" data-id="{{ $infoGrupo->id }}"> <i class="mdi mdi-delete"></i> @lang('buttons.general.unlink') </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="alert alert-info"> <i class="mdi mdi-alert-circle"></i> Não existem grupos vinculados.
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="form-actions mt-4">
                        <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> @lang('buttons.portal.empresa-processo-grupo.save-filters')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
@endsection


@section('footer')
    {{-- MultiSelect --}}
    <link href="{{ asset('plugins/multiselect/css/multi-select.css') }}" rel="stylesheet" type="text/css" />
    <script src="{{ asset('plugins/multiselect/js/jquery.multi-select.js') }}" type="text/javascript" ></script>
    <script src="{{ asset('plugins/quicksearch/jquery.quicksearch.js') }}" type="text/javascript" ></script>
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>

    <script>

        // MULTISELECT
        $('#grupos_a_vincular').multiSelect({ 
            keepOrder: true,
            selectableHeader: "<input type='text' class='form-control search-input' autocomplete='off' placeholder='Pesquisar grupos do sistema'>",
            selectionHeader: "<input type='text' class='form-control search-input' autocomplete='off' placeholder='Pesquisar grupos selecionados'>",
            afterInit: function(ms){
                var that = this,
                    $selectableSearch = that.$selectableUl.prev(),
                    $selectionSearch = that.$selectionUl.prev(),
                    selectableSearchString = '#'+that.$container.attr('id')+' .ms-elem-selectable:not(.ms-selected)',
                    selectionSearchString = '#'+that.$container.attr('id')+' .ms-elem-selection.ms-selected';

                that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
                .on('keydown', function(e){
                if (e.which === 40){
                    that.$selectableUl.focus();
                    return false;
                }
                });

                that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
                .on('keydown', function(e){
                if (e.which == 40){
                    that.$selectionUl.focus();
                    return false;
                }
                });
            },
            afterSelect: function(){
                this.qs1.cache();
                this.qs2.cache();
            },
            afterDeselect: function(values){
                this.qs1.cache();
                this.qs2.cache();
            }
        });

        $('#select-all').click(function() {
            $('#grupos_a_vincular').multiSelect('select_all');
            return false;
        });
        $('#deselect-all').click(function() {
            $('#grupos_a_vincular').multiSelect('deselect_all');
            return false;
        });

        // Removendo a classe que tornava o multiselect tamanho único (e permitindo que ocupe a tela / width inteira)
        $("#ms-grupos_a_vincular").css("width", "auto");


        // DATATABLE
        $(document).ready(function() {
            $('#dataTable-empresa-usuarios').DataTable({
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
                    { extend: 'excel',  text: 'Excel' },
                    { extend: 'pdf',    text: 'PDF' },
                    { extend: 'print',  text: 'Imprimir' }
                ]
            });
        });

        
        // Exclusão de víncula entre empresa-processo e grupo
        $('.sa-warning').click(function(){
            let idVinculo = $(this).data('id');
            let deleteIt = swal2_warning("Essa ação é irreversível!");
            let obj = {'vinculo_id': idVinculo};

            deleteIt.then(resolvedValue => {
                ajaxMethod('POST', "{{ URL::route('portal.empresa-processo-grupo.deletar') }}", obj).then(response => {
                    if(response.response != 'erro') {
                        swal2_success("Excluído!", "Vínculo entre empresa-processo e grupo excluído com sucesso.");
                    } else {
                        swal2_alert_error_support("Tivemos um problema ao excluir o vínculo.");
                    }
                }, error => {
                    swal2_alert_error_support("Tivemos um problema ao excluir o vínculo.");
                    console.log(error);
                });
            }, error => {
                swal.close();
            });
        });        
        
        
        $("#update-form").submit(function(e){
            e.preventDefault();

            let arrayFiltros = {};

            $($("#dataTable-empresa-processo-grupo tbody tr")).each(function(){       
                let idEmpresaProcessoGrupo = $(this)[0].dataset.idEmpresaProcessoGrupo;
                let identificador = '';
                let valor = '';
                
                if (typeof arrayFiltros[idEmpresaProcessoGrupo] != "object") {
                    arrayFiltros[idEmpresaProcessoGrupo] = {};
                }
                
                $(this).find('select, input').each(function(){       
                    identificador = $($(this)).data("identificador");
                    valor = $(this).val()

                    if (valor) {
                        arrayFiltros[idEmpresaProcessoGrupo][identificador] = valor
                    }
                });

            });

            $("#filters").val(JSON.stringify(arrayFiltros));

            $('#update-form').unbind('submit').submit();
        });

    </script>
@endsection