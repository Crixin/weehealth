$(document).on("click",".config_grafico", function() {
    let id = $(this).data('id');
    $('#config').val(id);
    carregaConfigGrafico($('#'+id).val()).then( ()=>{
        $('#modalConfigDashboard').modal('show');
    }).catch(function(error_msg){
        console.log("#### ERROR CARREGAR CONFIGURAÇÕES DASH! ####");
        console.log("ERROR ao carregar configurações dash: "+ error_msg);
    });
});

/*Funções*/
function save(name,submit)
{   
    validacao().then(()=>{
        let nome = swal2_input("Nome do dashboard","Digite o nome","Informe o nome do dashboard");
        $('.sweet-alert input').val($('#'+name).val());
        nome.then(resolvedValue => {
            $('#'+name).val(resolvedValue);
            return geraParametroDash();
        }).catch(function(error_msg){
            swal.close();
        }).then(() => {
            $('#'+submit).submit();
        }).catch(function(error_msg){
            console.log("#### ERROR GERAR COORDENADAS DASH! ####");
            console.log("ERROR ao gerar coordenadas dash: "+ error_msg);
        });
    }).catch(function(error_msg){
        console.log("#### ERROR NA VALIDACAO! ####");
        console.log("ERROR na validacao: "+ error_msg);
    });
}

function addWidget(config) {
    idGrafico +=1;
    grid.addWidget('<div id="grafico'+idGrafico+'"><div class="grid-stack-item-content btn config_grafico" style="border-color: #26c6da" data-id="grafico'+idGrafico+'" ></div><div><span style="font-size:12px;margin-left:5%">'+config+'</span><i class="fa fa-cog"  aria-hidden="true" style="margin-left:2%"></i></div></div>', 0, 0, 4, 4, true);
}

function geraParametroDash() {
    return new Promise((resolve,reject)=>{
        serializedData = [];
        grid.engine.nodes.forEach(function(node) {
            serializedData.push({
                x: node.x,
                y: node.y,
                width: node.width,
                height: node.height,
                config: $('#'+node.el.id).val()
            });
        });
        //ordenacao dos dashboards
        serializedData.sort(function(a, b) {
            return a.x - b.x;
        });

        if (!serializedData) reject(false);
        
        document.querySelector('#saved-data').value = JSON.stringify(serializedData, null, '  ');
        resolve(true);
    });    
}

function carregaConfigGrafico(inf)
{   
    $('#itensTable').empty();
    return new Promise((resolve, reject) => {
        console.log(inf.tituloGrafico)
        var tituloGraficoCarrega = (inf.tituloGrafico) ?  inf.tituloGrafico.replace('&nbsp;',' ') : '';
        var subTituloGraficoCarrega = (inf.subTituloGrafico) ? inf.subTituloGrafico.replace('&nbsp;',' ') : '';

        $('#tituloGrafico').val(tituloGraficoCarrega);
        $('#subTituloGrafico').val(subTituloGraficoCarrega);
        $('#caminhoApi').val(inf.caminhoApi);
        $('#tipoGrafico').val(inf.tipoGrafico).selectpicker('refresh');
        $('#periodoGrafico').val(inf.periodoGrafico).selectpicker('refresh');
        $('#tipoConsultaGrafico').val(inf.tipoConsultaGrafico).selectpicker('refresh');     

        changeTipoGrafico();

        var areaSelect = [];
        let idProcesso = "";
        let idEmpresa = "";
        
        if (inf.areaGrafico) {
            inf.areaGrafico.forEach(element => {
                areaSelect.push(element.idArea);
                idProcesso = element.idProcesso
                idEmpresa = element.idEmpresa
            });
            $('#areaGrafico').removeAttr('disabled').selectpicker('refresh');
        } else {
            $('#areaGrafico').attr('disabled',true).selectpicker('refresh');
        }
        $('#areaGrafico').val(areaSelect).selectpicker('refresh');
            
        //carrega table
        if(inf.tipoConsultaGrafico == 2) {
            buscaIndiceRegistro(idEmpresa, idProcesso).then(function(indice) {

                $('#addIndice').show();
                $('#divIndiceValor').show();
                $('#areaGrafico').selectpicker({maxOptions:1});
                $('.bs-deselect-all, .bs-select-all').css('display','none');

                listaIndice = JSON.parse(indice.indice_filtro_utilizado);
                /*Lista Indices */
                let montaListaIndice = '';
                let montaMultiValorado = '';
                inf.indiceValor.forEach(element => {
                    let contador = Number($('#contadorIndice').val()) + 1;
                    let mask = arrayTipoIndiceGED[element.tipoIndice].mask;
                    let type = arrayTipoIndiceGED[element.tipoIndice].htmlType;
                    let valor = '';
                    montaListaIndice = '';
                    montaMultiValorado = '';
                    
                    listaIndice.forEach(listaBD => {
                        listaBD = JSON.parse(listaBD);
                        let selecionado = (listaBD.identificador == element.indice) ? 'selected' : '';
                        
                        montaListaIndice += "<option " + selecionado + " value='" + listaBD.identificador + "|" + listaBD.descricao.replace('&nbsp;',' ') + "|" + listaBD.idTipoIndice + "' data-multivalorado='" + JSON.stringify(listaBD.listaMultivalorado) + "' >" + listaBD.descricao + '</option>';

                        if((element.indice == listaBD.identificador) && listaBD.idTipoIndice == 12 ){
                            let listaMultivalorado = listaBD.listaMultivalorado;
                            for (let j = 0; j < listaMultivalorado.length; j++) {
                                let selecionadoMult = (listaMultivalorado[j].descricao == element.valor) ? 'selected' : '';
                                montaMultiValorado += '<option '+selecionadoMult+' value="'+listaMultivalorado[j].descricao+'">'+listaMultivalorado[j].descricao+'</option>';
                            }
                        }
                    });
                    /*Valor Indice */
                    switch (element.tipoIndice) {
                        case "1":
                            let bolSim = (element.valor == 'true') ? 'selected' : '';
                            let bolNao = (element.valor == 'false') ? 'selected' : '';
                            valor = '<select class="form-control valor" id="valorIndice'+contador+'" required><option '+bolSim+' value="true">Sim</option><option '+bolNao+' value="false">Não</option></select>';
                            break;

                        case "5":
                        case "6":
                            /* let options = '<option value="1" ' + (element.valor == 1 ? "selected" : "" ) + '>Dia Atual</option>';
                            options += '<option value="2" ' + (element.valor == 2 ? "selected" : "" ) + '>Dia Anterior</option>';
                            options += '<option value="3" ' + (element.valor == 3 ? "selected" : "" ) + '>Semana Anterior</option>';
                            options += '<option value="4" ' + (element.valor == 4 ? "selected" : "" ) + '>Mês Atual</option>';
                            options += '<option value="5" ' + (element.valor == 5 ? "selected" : "" ) + '>Mês Anterior</option>';
                            options += '<option value="6" ' + (element.valor == 6 ? "selected" : "" ) + '>Bimestre</option>';
                            options += '<option value="7" ' + (element.valor == 7 ? "selected" : "" ) + '>Trimestre</option>';

                            valor = '<select class="form-control valor" id="valorIndice'+contador+'" required>'+options+'</select>'; */
                            valor = '<label> Será considerado o valor do período </label>';

                            break;
                        case "12":
                            valor = '<select class="form-control valor" id="valorIndice'+contador+'" required>'+montaMultiValorado+'</select>';
                            break;
                        default:
                            valor = '<input type="text" id="valorIndice'+contador+'" class="form-control valor" value="'+element.valor+'"></input>';
                            break;    
                    }
    
                    $('#itensTable').append('<tr id="linha'+contador+'"><td><select data-id="'+contador+'" id="selectIndice'+contador+'" class="selectpicker form-control indice" data-live-search="true" data-actions-box="true">'+montaListaIndice+'</select></td><td id="tdValor'+contador+'">'+valor+'</td><td><i class="fa fa-minus-circle btn btn-danger remove"  data-id="'+contador+'" title="Clique para remover o índice." style="margin-left:2px;font-size:20px;margin-bottom:5px"></i></td></tr>');
                   
                    $('#selectIndice'+contador+'').selectpicker('refresh');
                    $('#contadorIndice').val(contador);
                    //mascara
                    $('#valorIndice'+contador).attr('type',type);
                    if(arrayTipoIndiceGED[element.tipoIndice].cssClass){
                        $('#valorIndice'+contador).mask(mask, {reverse: true});
                    }
                });
                $('#divIndiceValor').show();
               
            }).catch(function(error_msg){
                console.log("#### ERROR AO BUSCAR INDICE DA AREA! ####");
                console.log("ERROR ao buscar índice da área: "+ error_msg);
            }); 
        }
        resolve(true);
    });
}

function validacao()
{
    return new Promise((resolve,reject) => {
        var incompleto = false;
        $('.grid-stack-item-content').each(function(){
            var id = $(this).attr('data-id');
            
            if($('#'+id).val() == '' && id != 'undefined') {
                swal2_alert_error_not_reload('Existe configurações não preenchidas, verifique!');
                incompleto = true;
                return false;
            }
        });
        if(incompleto) reject(false);
        resolve(true);
    });
}

function changeTipoGrafico()
{
    if ($("#tipoGrafico").val() == "customizado") {
        $(".caminho-api").show() 
    } else {
        $(".caminho-api").hide();
    } 
}
