$(document).ready(function(){
    
    var listaIndice = '';

    $(document).on('show.bs.modal', '.modal', function () {
        ( $('#itensTable').find('td').length <= 0 ) ? $('#divIndiceValor').hide() : $('#divIndiceValor, #addIndice').show();
    });
    
    $(document).on("click","#save", function() {
        var id = $('#config').val();
        montaConfigGrafico()
        .then((configuracao) => {
            $('#'+id).val(configuracao);
            $('#saveDashboard').attr('disabled',false);
            $('#modalConfigDashboard').modal('hide');
            return limpaConfigGrafico();
        }).catch(function(error_msg){
            console.log("#### ERROR MONTA CONFIGURAÇÃO! ####");
            console.log("ERROR ao montar configuração: "+ error_msg);
        });
    });


    $("#tipoGrafico").change(function(){
        changeTipoGrafico();
    })


    $('#tipoConsultaGrafico').on('change',function(){
        $('#itensTable').empty();
        $('#contadorIndice').val(0);
        $('#areaGrafico').val('').removeAttr('disabled').selectpicker('refresh');
        $('#addIndice').hide();

        if($(this).val() == 2){
            //unica área
            $('#divIndiceValor').show();
            $('#areaGrafico').selectpicker({maxOptions:1});
            $('#areaGrafico').selectpicker('refresh');
            $('.bs-deselect-all, .bs-select-all').css('display','none');
        }else{
            //varias áreas
            $('#divIndiceValor').hide();
            $('#areaGrafico').selectpicker({maxOptions:4});
            $('#areaGrafico').selectpicker('refresh');
            $('.bs-deselect-all, .bs-select-all').css('display','block');
        }
    });

    $('#areaGrafico').on('change',function(){
        $('#itensTable').empty();
        $('#addIndice').show();

        $.each($("#areaGrafico option:selected"), function(){            
            let processo = JSON.parse($(this).attr('data-processo'));
            let empresa = JSON.parse($(this).attr('data-empresa'));

            $("#idEmpresa").val(empresa.id);
            $("#idProcesso").val(processo.id);
        });
    });

    $('#addIndice').on('click',function(){
        var contador = Number($('#contadorIndice').val()) + 1;
        let filtros = "";
        
        $.each($("#areaGrafico option:selected"), function(){            
            let processo = JSON.parse($(this).attr('data-processo'));

            filtros = JSON.parse(processo.pivot.indice_filtro_utilizado);
        });

        $('#itensTable').append('<tr id="linha'+contador+'"><td><select data-id="'+contador+'" id="selectIndice'+contador+'" class="selectpicker form-control indice" data-live-search="true" data-actions-box="true"><option value="" >Selecione</option></select></td><td id="tdValor'+contador+'"></td><td><i class="fa fa-minus-circle btn btn-danger remove"  data-id="'+contador+'" title="Clique para remover o índice." style="margin-left:2px;font-size:20px;margin-bottom:5px"></i></td></tr>');
       
        $('#selectIndice'+contador+'').selectpicker('refresh');
        $('#contadorIndice').val(contador);

        filtros.forEach(element => {
            element = JSON.parse(element)
            $('#selectIndice'+contador).append("<option value='" + element.identificador + "|" + element.descricao + "|" + element.idTipoIndice + "|" + element.idAreaReferenciada + "' data-multivalorado='" + JSON.stringify(element.listaMultivalorado) + "' >" + element.descricao + "</option>").selectpicker('refresh');
        });
    });
    
    $(document).on("click",".remove", function() {
        var id = $(this).data('id');
        $('#linha'+id).remove();
    });

    $(document).on("change",".indice",function(e){
        var id = $(this).data('id');
        var valor = $('#selectIndice'+id).val();
        if (valor) {
            var aux = valor.split('|');
            var tipoIndice = aux[2];
            let idAreaReferenciada = aux[3];
            var mask = arrayTipoIndiceGED[tipoIndice].mask;
            var type = arrayTipoIndiceGED[tipoIndice].htmlType;

            let listaMultivalorado = e.currentTarget.selectedOptions[0].dataset.multivalorado; 

            switch (tipoIndice) {
                case "1":
                case 1:
                    //tipo boolean
                    linha = "<select class='form-control' required>";                            
                    
                    $.each(infoInput.selectOptions, function(key, val){
                        linha += "<option value=" + key + ">" + val + "</option>"
                    });
                    linha += "</select>";

                    $('#tdValor'+id).empty().append(linha);

                    break;

                case "5":
                case "6":
                case 5:
                case 6:
                    //DATA E DATA E HORA 

                    /* linha = "<select class='form-control' required>";    
                    linha += '<option value="">Selecione</option>';
                    linha += '<option value="1">Dia Atual</option>';
                    linha += '<option value="2">Dia Anterior</option>';
                    linha += '<option value="3">Semana Anterior</option>';
                    linha += '<option value="4">Mês Atual</option>';
                    linha += '<option value="5">Mês Anterior</option>';
                    linha += '<option value="6">Bimestre</option>';
                    linha += '<option value="7">Trimestre</option>';
                    linha += "</select>";

                    $('#tdValor'+id).empty().append(linha); */
                    let label = '<label> Será considerado o valor do período </label>';
                    $('#tdValor'+id).empty().append(label);

                    break;
                case "12":
                case 12:
                    //tipo multivalorado
                    linha = "<select class='form-control' required>";
                    $.each(JSON.parse(listaMultivalorado), function(key, val){
                        linha += "<option value=" + val.valor + ">" + val.descricao + "</option>"
                    });
                    linha += "</select>";

                    $('#tdValor'+id).empty().append(linha);


                    break;

                case "17":
                case 17:
                    $.ajax({
                        url: '/ged/buscaInfoArea?idArea=' + idAreaReferenciada,
                        type: 'GET',
                        dataType: 'JSON',
                        headers: {
                            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            $.each(response.response[0].listaIndicesRegistro, function(key, el) {
                                if (el.identificador == valorSelecionado) {

                                    infoInput = arrayTipoIndiceGED[el.idTipoIndice];

                                    $(tr).append("<input type='" + infoInput.htmlType + "' class='form-control " + infoInput.cssClass + " ' required />");
                    
                                    if (infoInput.cssClass) {
                                        $("." + infoInput.cssClass).mask(infoInput.mask, {reverse: true});
                                    }
                                }
                            });
                        },   
                        error: function(error){
                            //console.log(error);
                        }
                    });
                    
                    break;

                default:
                    $('#tdValor'+id).empty().append('<input type="text" id="valorIndice'+id+'" class="form-control valor" required="true" data-nome="Campo valor do índice é obrigatório.">');
                    $('#valorIndice'+id).attr('type',type);
                    if(arrayTipoIndiceGED[tipoIndice].cssClass){
                        $('#valorIndice'+id).mask(mask, {reverse: true});
                    }
                    break;
            }
        }
    });
    
    /*Funções*/
    function montaConfigGrafico()
    {
        return new Promise((resolve,reject)=>{
            var tituloGrafico       = $('#tituloGrafico').val().replace(/ /g,'&nbsp;');
            
            let subTituloGrafico    = $('#subTituloGrafico').val().replace(/ /g,'&nbsp;');
            var tipoGrafico         = $('#tipoGrafico').val();
            var caminhoApi          = $('#caminhoApi').val();
            var periodoGrafico      = $('#periodoGrafico').val();
            var tipoConsultaGrafico = $('#tipoConsultaGrafico').val();
            
            validacao().then(()=>{
                var areaGrafico = [];
                var selectArea  = document.getElementById('areaGrafico');
                var j = 0;
                for (let index = 0; index < selectArea.options.length; index++) {
                    if(selectArea.options[index].selected == true && selectArea.options[index].value != ''){
                        areaGrafico[j] = {
                            'idArea' : selectArea.options[index].value,
                            'idEmpresa' : selectArea.options[index].dataset.idempresa,
                            'idProcesso' : selectArea.options[index].dataset.idprocesso,
                            'nome'   : selectArea.options[index].text.replace(/ /g,'&nbsp;')
                        }
                        j++;
                    }
                    
                }
                var indiceValor = [];
                var i = 0;
                $(".indice").each(function( index ) {
                    var id = $(this).data('id');
                    if(id){
                        var split = $('#selectIndice'+id).val().split('|');
                        indiceValor[i] = {
                            'indice'   : split[0],
                            'valor'    : $('#valorIndice'+id).val(),
                            'descricao': split[1].replace(/ /g,'&nbsp;'),
                            'tipoIndice' : split[2]
                        }
                        i++;
                    }
                });

                var configuracao =
                {
                    tituloGrafico      : tituloGrafico,
                    subTituloGrafico   : subTituloGrafico,
                    tipoGrafico        : tipoGrafico,
                    caminhoApi         : caminhoApi,
                    periodoGrafico     : periodoGrafico,
                    tipoConsultaGrafico: tipoConsultaGrafico,
                    areaGrafico        : areaGrafico,
                    indiceValor        : indiceValor
                };
                resolve(configuracao);
            }).catch(function(error_msg){
                reject(false);
                console.log("#### ERROR NA VALIDACAO! ####");
                console.log("ERROR na validacao: "+ error_msg);
            }); 
        });
    }

    function validacao()
    {
        return new Promise((resolve,reject) => {
            var incompleto = false;
            $('#configDashboard').find('[required=true]').each(function(){
                if(!$(this).val() || (typeof $(this).val() == 'object'  && isEmpty($(this).val())) ){
                    var id = $(this).attr('id');
                    var nome = $(this).data('nome');
                    $('#'+id).focus();
                    swal2_alert_error_not_reload('O campo ' + nome + ' é obrigatório!');
                    incompleto = true;
                    return false;  
                }
            });

            
            if($('#tipoConsultaGrafico').val() == 2 && jQuery('table tbody tr').length == 0) {
                
                incompleto = true;
                swal2_alert_error_not_reload('Informe algum índice!');
                return false;  
            }

            if(incompleto) reject(false);
            resolve(true);
        });
    }

    function limpaConfigGrafico()
    {
        return new Promise((resolve,reject)=>{
            $('#itensTable').empty();
            $('#contadorIndice').val(0);
            $('#tipoConsultaGrafico').val(1).selectpicker('refresh');
            $('#tituloGrafico, #subTituloGrafico, #tipoGrafico, #periodoGrafico, #areaGrafico, #idEmpresa, #idProcesso, #caminhoApi').val('').selectpicker('refresh');
            resolve(true);
        });
    }

})

function buscaIndiceRegistro(empresa, processo)
{
    return new Promise((resolve,reject)=>{
        $.ajax({
            url: '/buscar/processByEnterpriseAndProcesso?empresa=' + empresa + '&processo=' + processo,
            type: 'GET',
            dataType: 'JSON',
            success: function (data) {
                resolve(data);
            },
            error: function(error){
                reject(error);
            }
        });
    });  
}

function isEmpty(obj) {
    for(var prop in obj) {
        if(obj.hasOwnProperty(prop))
            return false;
    }

    return true;
}