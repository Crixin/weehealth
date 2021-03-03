@extends('layouts.app')




@section('page_title', __('page_titles.portal.enterprise.linked_processes'))


@section('breadcrumbs')

    <li class="breadcrumb-item"><a href="{{ route('core.home') }}"> @lang('page_titles.general.home') </a></li>
    <li class="breadcrumb-item"><a href="{{ route('portal.empresa') }}"> @lang('page_titles.portal.enterprise.index') </a></li>
    <li class="breadcrumb-item active"> @lang('page_titles.portal.enterprise.linked_processes') </li>    

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

                <form method="POST" action="{{ route('portal.empresa.vincularProcessos') }}"  >
                    {{ csrf_field() }}
                    <input type="hidden" name="idEmpresa" value="{{ $empresa->id }}">
                    
                    <div class="form-body">

                        {{-- Parte 1: cadastro --}}
                        <h3 class="box-title"> @lang('page_titles.portal.enterprise.processes_available') <small> - Escolha um processo e selecione uma área</small>  </h3>
                        <hr class="m-t-0 m-b-10">
                        
                        @if ($processosRestantes->count() > 0)
                            <div class="row p-t-20 m-b-40">
                                <div class="col-md-5 text-center">
                                    @foreach ($processosRestantes as $processo)
                                        <div class="col-md-12 m-t-10 m-b-40">
                                            <input type="radio" name="processo_selecionado" id="radio_{{ $processo->id }}" value="{{ $processo->id }}" class="radio-col-light-blue with-gap" />
                                            <label for="radio_{{ $processo->id }}" style="font-size: xx-large">{{ $processo->nome }}</label>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="col-md-7">
                                    <div id="treeGedNodes" class=""></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group{{ $errors->has('indice_filtro_utilizado') ? ' has-error' : '' }}">
                                        <label class="control-label">Filtros de Utilização</label>
                                        <input type="hidden" name="headersTable" id="headersTable" />
                                        <select class="form-control selectpicker" name="indice_filtro_utilizado[]" id="indice_filtro_utilizado"  required data-live-search="true" data-size='10' data-actions-box="true" multiple></select>

                                        <small class="form-control-feedback"> Selecione quais devem ser os filtros deste processo. </small> 
                                    </div>
                                </div>
                                <div class="col-md-2 ">
                                    <label class="control-label"> &nbsp;</label><br>
                                    <button type="button" onclick="buscaFiltros();" class="btn btn-info"> Buscar filtros</button>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> @lang('buttons.general.save')</button>
                                <a href="{{ route('portal.empresa') }}" class="btn btn-inverse"> @lang('buttons.general.back')</a>
                            </div>
                        @else
                            <div class="row p-t-20 m-b-40">
                                <div class="col-md-12 text-center">
                                    <div class="alert alert-warning"> <i class="mdi mdi-alert-circle"></i> A empresa <b>{{ $empresa->nome }}</b> já possui todos os processos vinculados à alguma área ou ainda não foram cadastrados processos no sistema.
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions">
                                <a href="{{ route('portal.empresa') }}" class="btn btn-inverse"> @lang('buttons.general.back')</a>
                            </div>
                        @endif

                        {{-- Parte 2: listagem --}}
                        <h3 class="box-title m-t-40"> @lang('page_titles.portal.enterprise.linked_processes_to')  <span style="font-weight: bold;">{{ $empresa->nome }}</span> </h3>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                @if ($processosVinculados->count() > 0)    
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Nome do Processo</th>
                                                    <th>Grupos</th>
                                                    {{-- <th>ID da Área</th> --}}
                                                    <th>Excluir Vínculo</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($processosVinculados as $processoV)
                                                
                                                    <tr>
                                                        <td class="text-bold"><b> {{ $processoV->portalProcesso->nome }} </b></td>
                                                        {{-- <td> {{ $processoV->id_area_ged }} </td> --}}
                                                        <td>
                                                            <a href="{{ route('portal.empresa-processo-grupo.criar', ['empresaProcesso' => $processoV->id]) }}" class="btn waves-effect waves-light btn-info"> <i class="mdi mdi-link"></i> @lang('buttons.general.link') </a>
                                                        </td>
                                                        <td> 
                                                            <a href="#" class="btn waves-effect waves-light btn-danger sa-warning" data-id="{{ $processoV->id }}"> <i class="mdi mdi-delete"></i> @lang('buttons.general.delete') </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="alert alert-info"> <i class="mdi mdi-alert-circle"></i> A empresa <b>{{ $empresa->nome }}</b> ainda não possui nenhum processo vinculado.
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                </form>


            </div>
        </div>
    </div>
    
@endsection


@section('footer')
    
    <!-- jQuery Loading Plugin -->
    <link href="{{ asset('plugins/jquery-loading/jquery.loading.min.css') }}" rel="stylesheet">
    <script src="{{ asset('plugins/jquery-loading/jquery.loading.min.js') }}"></script>

    <!-- soapClient JavaScript -->
    <script src="{{ asset('js/soapclient.js') }}"></script>


    <!-- Treeview Plugin JavaScript -->
    <script src="{{ asset('plugins/bootstrap-treeview-master/dist/bootstrap-treeview.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap-treeview-master/dist/bootstrap-treeview-init.js') }}"></script>

  
    {{-- Script para carregar áreas do GED (inicia pelas áreas-pai) --}}
    <script>

        let listaAreas = {!! json_encode($listaAreas, JSON_HEX_TAG) !!};

        $('#treeGedNodes').treeview({
            selectedBackColor: "#03a9f3",
            onhoverColor: "rgba(0, 0, 0, 0.05)",
            expandIcon: 'ti-plus',
            collapseIcon: 'ti-minus',
            nodeIcon: 'fa fa-folder',
            multiSelect: true,
            data: listaAreas,
            onNodeSelected(evt, data) {
                $('<input>').attr({
                    type: 'hidden',
                    id: 'foo',
                    name: 'id_area_ged[]', 
                    value: data.id
                }).appendTo('form');
            }
        });

    </script>

    <!-- SweetAlert2 -->
    <script>
        
        // Exclusão de vínculo entre empresa e processo
        $('.sa-warning').click(function(){
            let idEmpresaProcesso = $(this).data('id');
            let deleteIt = swal2_warning("Essa ação é irreversível!");
            let obj = {'vinculo_id': idEmpresaProcesso};

            deleteIt.then(resolvedValue => {
                ajaxMethod('POST', "{{ URL::route('portal.relacao.empresaProcesso.deletar') }}", obj).then(response => {
                    if(response.response != 'erro') {
                        swal2_success("Excluído!", "Vinculação entre empresa e processo excluída com sucesso.");
                    } else {
                        swal2_alert_error_support("Tivemos um problema ao excluir o vínculo.");
                    }
                }, error => {
                    console.log(error);
                });
            }, error => {
                swal.close();
            });
        });

        function buscaFiltros () {
            let areas = [];
            $.each($('#treeGedNodes').treeview('getSelected'), function (key, el){
                areas.push(el.id);
            });

            ajaxMethod("GET", "{{ route('portal.ged.getIndicesComumAreas') }}", {"listaAreas": areas}).then(resp => {
                if (!resp.error) {
                    resp = resp.response
                    
                    let options = "";
                    $("#headersTable").val(JSON.stringify(resp.listaIndices));
                    $.each(resp.listaIndices, function (idx, el) {
                        options += "<option value='" + JSON.stringify(el) + "' >" + el.descricao + "</option>";
                    });
                    $("#indice_filtro_utilizado").empty();
                    $("#indice_filtro_utilizado").append(options);
                    
                    options = $("#indice_filtro_utilizado option"); 

                    options.appendTo("#indice_filtro_utilizado"); 
                    
                    $("#indice_filtro_utilizado").selectpicker("refresh");                    

                }
            });

        }



    </script>
@endsection