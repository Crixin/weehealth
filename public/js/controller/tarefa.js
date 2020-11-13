$('#area').on('change', function(){
    $('#tipo_indexacao').removeAttr('disabled').selectpicker('refresh');
    if($('#tipo_indexacao').val()){
        populaIndices();
    }
});

$('#tipo_indexacao').on('change', function(){
    $('#table').show();
    populaIndices(); 
});

$("#frequencia").change(function () {
    $("#hora").prop('readonly', !($(this).val() == "dailyAt"));
});

$('.btn-success').on('click', function(event){
    event.preventDefault();
    montaConfiguracao().then(config =>{
        $('#indices').val(JSON.stringify(config));
        $('#formTarefa').submit();
    }).catch(function(error_msg){
        console.log("#### ERROR MONTA CONFIGURAÇÃO! ####");
        console.log("ERROR ao montar configuração: "+ error_msg);
    });
    
});

$(document).on("click",".selecionado", function() {
    let id = $(this).data('id');
    $('#selecionado-'+id).is(':checked') ? $('#tdPosicao'+id).show() : $('#tdPosicao'+id).val('').hide();
});

function buscaIndiceRegistro(id)
{
    return new Promise((resolve,reject)=>{
        $.ajax({
            url: '/portal/ged/buscaInfoArea?idArea='+id+'&params=?filhas=false',
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


function montaConfiguracao()
{
    return new Promise((resolve,reject)=>{
        var indiceValor = [];
        var i = 0;
        $(".indice").each(function( index ) {
            var id = $(this).data('id');
            if(id){
                indiceValor[i] = {
                    'selecionado'   : $('#selecionado-'+id).is(':checked'),
                    'nomeIndice'    : $('#indice'+id).text(),
                    'indexador'     : $('#indexador'+id).is(':checked'),
                    'identificador' : $('#indice'+id).data('identificador'),
                    'autoupdate'    : $('#autoupdate'+id).is(':checked'),
                    'posicao'       : $('#selecionado-'+id).is(':checked') ? $('#posicao'+id).val() : '',
                    'tipoIndice'    : $('#tipoIndice'+id).val()
                }
                i++;   
            }
        });
        var configuracao =
        {
            indice      : indiceValor,
        };
        console.log(configuracao);
        resolve(configuracao);
    });
}

function populaIndices()
{
    let tipo   = $('#tipo_indexacao').val();
    let idArea = JSON.parse($('#area').val());
    $('#tableIndices').empty();

    if (idArea.length == 1) {
        buscaIndiceRegistro(idArea).then(function(indice){
            listaIndice = (tipo == "REGISTRO") ? indice.response[0].listaIndicesRegistro : indice.response[0].listaIndiceDocumento;
            let i = 1;
            listaIndice.forEach(element => {
                $('#tableIndices').append('<tr data-id="'+i+'" class="indice"><td>'+i+'</td><td><input type="checkbox" data-id="'+i+'"  id="selecionado-'+i+'" class="selecionado filled-in chk-col-cyan"/><label for="selecionado-'+i+'">Selecionado</label></td><td id="indice'+i+'" data-identificador="'+element.identificador+'">'+element.descricao+'</td><input type="hidden" name="tipoIndice'+i+'" id="tipoIndice'+i+'" value="'+element.idTipoIndice+'"><td><input  type="checkbox" id="indexador'+i+'" class="filled-in chk-col-cyan" ><label for="indexador'+i+'">Sim</label></td><td><input  type="checkbox" id="autoupdate'+i+'" class="filled-in chk-col-cyan" ><label for="autoupdate'+i+'">Ativo</label></td><td id="tdPosicao'+i+'" style="display:none"><input id="posicao'+i+'" type="number" min="0" value="'+i+'" class="form-control"></td></tr>');
                i++;
            });
        }).catch(function(error_msg){
            console.log("#### ERROR AO BUSCAR INDICE DA AREA! ####");
            console.log("ERROR ao buscar índice da área: "+ error_msg);
        });  
    } else {
        $("#area").selectpicker('val', '');
        swal2_alert_error_not_reload("Selecione um processo que possua somente uma área vinculada");
    }

}
