let valor = $('#tipoConfiguracao').val();
if( valor != ''){
    carregaCampo(valor);
}

$('#tipoConfiguracao').on('change',function(){
    let tipo = $(this).val();
    carregaCampo(tipo);   
})

$( "#visualizaSenha" ).mousedown(function() {
    $("#senha").attr("type", "text");
   
});

$( "#visualizaSenha" ).mouseup(function() {
    $("#senha").attr("type", "password");
    
});
$( "#visualizaSenha" ).mouseout(function() {
    $("#senha").attr("type", "password");
    
});

function carregaCampo(tipo){
    if(tipo == "FTP"){
        $('#divPastaSistema').hide();
        $('#divFTP').show();
        $('#ip, #porta, #usuario, #senha').attr('required', true);
        $('#pastaSistema').removeAttr('required').val('');
    }else{
        $('#divFTP').hide();
        $('#divPastaSistema').show();
        $('#pastaSistema').attr('required', true);
        $('#ip, #porta, #usuario, #senha').removeAttr('required').val('');
    }  
}