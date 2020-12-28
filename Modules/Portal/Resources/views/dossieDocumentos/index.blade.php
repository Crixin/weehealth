@extends('layouts.app')



@section('page_title', __('page_titles.portal.dossieDocumentos.index'))

@section('breadcrumbs')

    <li class="breadcrumb-item"><a href="{{ route('portal.home') }}"> @lang('page_titles.general.home') </a></li>    
    <li class="breadcrumb-item active"> @lang('page_titles.portal.dossieDocumentos.index') </li>    
    <link href="{{ asset('plugins/tag-input/bootstrap-tagsinput.css') }}" rel="stylesheet">


@endsection

@section('content')

	<div class="col-12">
		<div class="card">
			<div class="card-body">
				<div class="container">
					<div class="row">

                        {{-- Alertas pré-filtro --}}
     {{--                    <div class="col-6 m-b-30">
                            <div class="alert alert-info"> @lang('action.messages.filter_1_desc') </div>
                        </div>
                        <div class="col-6 m-b-30">
                            <div class="alert alert-info"> @lang('action.messages.filter_2_desc') </div>    
                        </div> --}}
                        
                        <hr>
                        
                        {{-- Campos de busca [form] --}}
						<div class="col"></div>
						<div class="col-12">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if(Session::has('message'))
                                @component('components.alert') @endcomponent
                                {{ Session::forget('message') }}
                            @endif
                            
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label class="control-label">Processos</label>
                                            <select name="empresas[]" id="empresas" class="form-control text-center selectpicker" required data-size="10" data-live-search="true" data-actions-box="true" multiple>
                                                @foreach ($empresas as $key => $empresa)
                                                    <optgroup value="{{ $empresa->id }}" label="{{$empresa->nome}}">
                                                    @foreach ($empresa->portalProcesses as $key => $processo)
                                                        <option value="{{ $empresa->id . $processo->id }}" data-processo="{{$processo}}" data-empresa="{{$empresa}}" > {{ $processo->nome }} </option>
                                                    @endforeach
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="control-label">&nbsp;</label>
                                        <button type="button" onclick="createNewFilter();" class="btn waves-effect waves-light btn-block btn-success pull-right"> Adicionar filtros</button>
                                    </div>
                                
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Separador de documentos</label>
                                            <select name="identificadorSelect" id="identificadorSelect" class="form-control text-center selectpicker" required data-size="10" data-live-search="true" data-actions-box="true">
                                                <option value="PASTA"> Pasta com ID's do registro</option>
                                                <option value="ARQUIVO"> Arquivos com ID's do registro</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Título / Nome</label>
                                            <input type="text" name="tituloSelect" id="tituloSelect" class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label"> Download direto / Link temporário </label>
                                            <select name="tipoEnvioSelect" id="tipoEnvioSelect" class="form-control text-center selectpicker" required data-size="10" data-live-search="true" data-actions-box="true">
                                                <option selected value="DOWNLOAD"> Download direto</option>
                                                <option value="10"> Link temporário de 10 (min) </option>
                                                <option value="30"> Link temporário de 30 (min) </option>
                                                <option value="60"> Link temporário de 60 (min) </option>
                                                <option value="120"> Link temporário de 120 (min) </option>
                                            </select>
                                        </div>
                                    </div>

                                    @if($pocHavan)
                                        <input type="hidden" name="destinatariosSelect" id="destinatariosSelect">
                                    @else
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="control-label"> Lista de emails para o <span class="font-weight-bold">link temporário</span> (para adicionar aperte Enter)</label>
                                                <br>
                                                <select multiple class="form-control" style="min-width: 100%" name='destinatariosSelect[]' id="destinatariosSelect" data-role="tagsinput"></select>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>             
                                                        
                            <form method="POST" action="{{ route('portal.dossie-documentos.download') }}" id="formDossie" name="formDossie" >
                                {{ csrf_field() }}
                                <input type="hidden" name="filters" id="filters"> 
                                <input type="hidden" name="identificador" id="identificador">
                                <input type="hidden" name="tipoEnvio" id="tipoEnvio">
                                <input type="hidden" name="destinatarios" id="destinatarios">
                                <input type="hidden" name="titulo" id="titulo">
                            </form>

                            <form method="POST" id="fakeFormDossie" name="fakeFormDossie">
                                {{ csrf_field() }}
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="control-label mt-2">Filtros</label>
                                            <div class="card-body">
                                                <table class="table table-striped table-bordered" id="table-filtro">
                                                    <thead>
                                                        <tr>
                                                            <th>Empresa</th>
                                                            <th>Processo</th>
                                                            <th>Índice</th>
                                                            <th>Valor</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="m-t-20">
                                        <button type="submit" class="btn waves-effect waves-light btn-lg btn-block btn-success pull-right"> @lang('buttons.general.search&Send') </button>
                                    </div>
                                </div>
                            </form>
						</div>
                        <div class="col"></div>
                        
					</div>
				</div>

			</div>
		</div>
	</div>
    
@endsection

@section('footer')
    <script src="{{ asset('plugins/tag-input/bootstrap-tagsinput.js') }}"></script>
    <script >

        var processosSelected = [];

        function createNewFilter() {   
            $.each($("#empresas option:selected"), function(){            
                
                let processo = JSON.parse($(this).attr('data-processo'));
                
                let empresa = JSON.parse($(this).attr('data-empresa'));

                let listaIndices = JSON.parse(processo.pivot.indice_filtro_utilizado)

                let areaGed = processo.pivot.id_area_ged;
                
                let linha = "<tr class='tr-table' data-area-ged='" + areaGed + "' data-id-empresa='" + empresa.id + "' data-id-processo='" + processo.id + "' >";

                linha += "<td>" + empresa.nome + "</td>";
                
                linha += "<td>" + processo.nome + "</td>";

                linha += "<td>";   

                linha += "<select class='form-control changeTypeDocument' required>";                            
                    
                linha += "<option value='0'> Selecione um tipo </option>";
            
                $.each(listaIndices, function(idx, element) {
                    element = JSON.parse(element);
                    linha += "<option data-id-area-referenciada='" + element.idAreaReferenciada + "' data-tipoindice='" + element.idTipoIndice + "' data-multivalorado='" + JSON.stringify(element.listaMultivalorado) + "' value=" + element.identificador + "> " + element.descricao + " </option>"
                })
                    
                linha += "</select>";

                linha += "</td>";
                
                linha += '<td><i class="mdi mdi-delete removeItem" ></i> </td>';

                linha += "</tr>";
                
                $("#table-filtro tbody").append(linha);

            }); 
        }
        
        $(document).on("change", ".changeTypeDocument", function (e) {   
            let tr = $(this).parent().parent();
            let arrayTipoIndiceGED = {!! json_encode($tiposIndicesGED, JSON_HEX_TAG) !!};

            $(tr).find("input, i, select").each(function() {
                if (!$(this).hasClass('changeTypeDocument')) {
                    $($(this).parent()).remove();
                }
            });

            let valorSelecionado = $(this).val()

            let idTipoIndice = e.currentTarget.selectedOptions[0].dataset.tipoindice;
            let listaMultivalorado = e.currentTarget.selectedOptions[0].dataset.multivalorado; 
            let idAreaReferenciada = e.currentTarget.selectedOptions[0].dataset.idAreaReferenciada; 

            let infoInput = arrayTipoIndiceGED[idTipoIndice];
            let linha = "";
            switch (idTipoIndice) {
                case "1":
                    //tipo boolean
                    linha = "<td>";
                    linha += "<select class='form-control' required>";                            
                    
                    $.each(infoInput.selectOptions, function(key, val){
                        linha += "<option value=" + key + ">" + val + "</option>"
                    });
                    linha += "</select>";

                    linha += "</td>";
                    $(tr).append(linha);
                    $(tr).append('<td><i class="mdi mdi-delete removeItem" ></i> </td>');

                    break;

                case "5":
                case "6":
                
                    $(tr).append("<td> <input type='" + infoInput.htmlType + "' class='form-control " + infoInput.cssClass + " ' required /></td> ");
                    
                    if (infoInput.cssClass) {
                        $("." + infoInput.cssClass).mask(infoInput.mask, {reverse: true});
                    }
                    
                    $(tr).append("<td> <input type='" + infoInput.htmlType + "' class='form-control " + infoInput.cssClass + " ' required /></td> ");
                    
                    if (infoInput.cssClass) {
                        $("." + infoInput.cssClass).mask(infoInput.mask, {reverse: true});
                    }

                    $(tr).append('<td><i class="mdi mdi-delete removeItem" ></i> </td>');
                
                    break;

                case "12":

                    linha = "<td>";
                    linha += "<select class='form-control' required>";                            
                    $.each(JSON.parse(listaMultivalorado), function(key, val){
                        linha += "<option value=" + val.valor + ">" + val.descricao + "</option>"
                    });
                    linha += "</select>";

                    linha += "</td>";
                    $(tr).append(linha);
                    $(tr).append('<td><i class="mdi mdi-delete removeItem" ></i> </td>');

                    break;

                case "17":
                    $.ajax({
                        url: '/portal/ged/buscaInfoArea?idArea=' + idAreaReferenciada,
                        type: 'GET',
                        dataType: 'JSON',
                        headers: {
                            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            $.each(response.response[0].listaIndicesRegistro, function(key, el) {
                                if (el.identificador == valorSelecionado) {

                                    infoInput = arrayTipoIndiceGED[el.idTipoIndice];

                                    $(tr).append("<td> <input type='" + infoInput.htmlType + "' class='form-control " + infoInput.cssClass + " ' required /></td> ");
                    
                                    if (infoInput.cssClass) {
                                        $("." + infoInput.cssClass).mask(infoInput.mask, {reverse: true});
                                    }
                                    $(tr).append('<td><i class="mdi mdi-delete removeItem" ></i> </td>');
                                }
                            });
                        },
                        error: function(error){
                            //console.log(error);
                        }
                    });
                    
                    break;
            
                default:

                    $(tr).append("<td> <input type='" + infoInput.htmlType + "' class='form-control " + infoInput.cssClass + " ' required /></td> ");
                    
                    if (infoInput.cssClass) {
                        $("." + infoInput.cssClass).mask(infoInput.mask, {reverse: true});
                    }

                    $(tr).append('<td><i class="mdi mdi-delete removeItem" ></i> </td>');
                    break;
            }

        })

        $(document).on("click", ".removeItem", function () {   
            $('.selectpicker').selectpicker('refresh');
            $($(this).parent().parent()).remove();
        });

        $("#fakeFormDossie").submit(function(e){
            e.preventDefault();

            let arrayFiltros = {};

            $($(".table tbody tr")).each(function(){       
                let areaGed = $(this)[0].dataset.areaGed;
                let idEmpresa = $(this)[0].dataset.idEmpresa;
                let idProcesso = $(this)[0].dataset.idProcesso;
                let idTipoIndice = '';
                let identificador = '';
                let idAreaReferenciada = '';
                let valor = '';

                $(this).find('select, input').each(function(){            
                    if ($(this).hasClass('changeTypeDocument')) {
                        let option = $(this).children("option:selected"); 
                        idTipoIndice = option.data('tipoindice')
                        idAreaReferenciada = option.data('idAreaReferenciada')
                        identificador = option.val()
                    } else {
                        valor = $(this).val()
                    }
                });
                
                if (typeof arrayFiltros[areaGed] != "object") {
                    arrayFiltros[areaGed] = [];
                }
                
                if (valor) {
                    arrayFiltros[areaGed].push({
                        'idTipoIndice': idTipoIndice,
                        'identificador': identificador,
                        'idAreaReferenciada': idAreaReferenciada,
                        'idEmpresa': idEmpresa,
                        'idProcesso': idProcesso,
                        'valor': valor
                    });
                }
            });

            $("#titulo").val($("#tituloSelect").val());
            $("#filters").val(JSON.stringify(arrayFiltros));
            $("#tipoEnvio").val($("#tipoEnvioSelect").val());
            $("#identificador").val($("#identificadorSelect").val());
            $("#destinatarios").val($("#destinatariosSelect").val());
            
            $("#formDossie").submit();
        });

    </script>

@endsection
