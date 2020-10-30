var grid = GridStack.init({
    alwaysShowResizeHandle: /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(
    navigator.userAgent
    ),
});

var cores = ['#26c6da','#1e88e5','#f4c63d','#d17905','#453d3f','#59922b','#0544d3','#6b0392','#f05b4f','#dda458','#eacf7d','#86797d','#b2c326','#6188e2','#a748ca'];

$( ".grafico" ).each(function( index ) {
    var id = index+1;
    var config = JSON.parse($('#configGrafico'+id).val());
    verificaGrafico(id,config);
});

function verificaGrafico(indexAdd,config)
{
    var config = JSON.parse($('#configGrafico'+indexAdd).val());
    switch (config.tipoGrafico) {
        case '1':
            montaGraficoLinha(indexAdd,config);
            break;
        case '2':
            montaGraficoBarra(indexAdd,config);
            break;
        case '3':
            montaGraficoPizza(indexAdd,config);
            break;
        case '4':
            montaGraficoTotal(indexAdd,config);
            break;
        case 'customizado':
            montaGraficoCustomizado(indexAdd,config);
            break;
    }
    animacaoGrafico();  
}

/*Modelos de Graficos Disponiveis*/
function montaGraficoLinha(id,config){
    var titulo    = config.tituloGrafico;
    var subTitulo = config.subTituloGrafico;
    var labels = [];
    var series = [];
    var itensLegenda = [];

    var subTituloVerificado = '';
    if(subTitulo) subTituloVerificado = '<h6 class="card-subtitle float-left">'+subTitulo+'</h6>';
    
    geradorParametros(config).then(params =>{
        let retorno = []
        for (let index = 0; index < params.length; index++) {
            p = new Promise((resolve, reject) => {
                let idArea  = params[index]['idArea'];
                let nomeArea= params[index]['nome'];
                let datas   = params[index]['datas'];
                let indices = params[index]['indices']; 
                
                montaConsultaGed(idArea,datas,indices).then(resultadoDatas =>{
                    let aux2 = {
                        'idArea': idArea,
                        'nome'  : nomeArea,
                        'datas' : resultadoDatas
                    }
                    retorno.push(aux2);
                    resolve(retorno);
                    
                });
            });
        }
        Promise.all([p]).then(retorno =>{
            if(retorno[0]){
                retorno[0].forEach(element => {
                    var seriesTmp = [];
                    itensLegenda.push(element.nome);
                    element.datas.forEach(elementDatas =>{
                        if(labels.indexOf(elementDatas.nome) == '-1') labels.push(elementDatas.nome);
                        seriesTmp.push(elementDatas.valor);
                    });
                    series.push(seriesTmp);
                });
            }
            //Monta Legenda
            var legenda = '';
            for (let index = 0; index < itensLegenda.length; index++) {
                legenda +=  '<li><h6 class="text-muted " style="color:'+cores[index]+' !important"><i class="fa fa-circle font-10 m-r-10 "></i>'+itensLegenda[index]+'</h6> </li>';
            }
            $('#bodyGrafico'+id).empty().append('<div class="d-flex flex-wrap"><div><h3 class="card-title">'+titulo+'</h3>'+subTituloVerificado+'</div><div class="ml-auto align-self-center"><ul class="list-inline m-b-0">'+legenda+'</ul></div></div><div id="grafico'+id+'" style="width:100%;height: 80%;" class=" ct-charts"></div>');
            
            var valores = {
                labels: labels,
                series: series
            };
            graficoLinhas('#grafico'+id,valores);
        });
    });  
}

function montaGraficoBarra(id,config){
    var titulo    = config.tituloGrafico;
    var subTitulo = config.subTituloGrafico;
    var labels = [];
    var series = [];
    var itensLegenda = [];

    var subTituloVerificado = '';
    if(subTitulo) subTituloVerificado = '<h6 class="card-subtitle float-left">'+subTitulo+'</h6>';
    //Busca Valores GED
    geradorParametros(config).then(params =>{
        let retorno = []
        for (let index = 0; index < params.length; index++) {
            p = new Promise((resolve, reject) => {
                let idArea  = params[index]['idArea'];
                let nomeArea= params[index]['nome'];
                let datas   = params[index]['datas'];
                let indices = params[index]['indices']; 
                montaConsultaGed(idArea,datas,indices).then(resultadoDatas =>{
                    let aux2 = {
                        'idArea': idArea,
                        'nome'  : nomeArea,
                        'datas' : resultadoDatas
                    }
                    retorno.push(aux2);
                    resolve(retorno); 
                });
            });
        }
        Promise.all([p]).then(retorno =>{
            if(retorno[0]){
                retorno[0].forEach(element => {
                    var seriesTmp = [];
                    itensLegenda.push(element.nome);
                    element.datas.forEach(elementDatas =>{
                        if(labels.indexOf(elementDatas.nome) == '-1') labels.push(elementDatas.nome);
                        seriesTmp.push(elementDatas.valor);
                    });
                    series.push(seriesTmp);
                });
            }     
            var legenda = '';
            for (let index = 0; index < itensLegenda.length; index++) {
                legenda += '<li><h6 class="text-muted " style="color:'+cores[index]+' !important"><i class="fa fa-circle font-10 m-r-10 "></i>'+itensLegenda[index]+'</h6></li>';
            }
            $('#bodyGrafico'+id).empty().append('<div class="row" style="width:100%;height: 100%;"><div class="col-12" style="height: 20%"><div class="d-flex flex-wrap"><div><h3 class="card-title">'+titulo+'</h3>'+subTituloVerificado+'</div><div class="ml-auto"><ul class="list-inline">'+legenda+'</ul></div></div></div><div class="col-12" style="width:100%;height: 80%;"><div id="grafico'+id+'" class="amp-pxl" style="width:100%;height: 100%;" ></div></div></div>');
        
            var valores = {
                labels: labels,
                series: series
            };
            graficoBarras('#grafico'+id,valores);
        });
    });
}

function montaGraficoPizza(id,config){
    var titulo    = config.tituloGrafico;
    var subTitulo = config.subTituloGrafico;

    var columns = [];
    var aux;
    
    var subTituloVerificado = '';
    if(subTitulo) subTituloVerificado = '<h6 class="card-subtitle float-left">'+subTitulo+'</h6>';
    //Busca Valores GED
    geradorParametros(config).then(params =>{
        let retorno = []
        for (let index = 0; index < params.length; index++) {
            p = new Promise((resolve, reject) => {
                let idArea  = params[index]['idArea'];
                let nomeArea= params[index]['nome'];
                let datas   = params[index]['datas'];
                let indices = params[index]['indices']; 
                
                montaConsultaGed(idArea,datas,indices).then(resultadoDatas =>{
                    let aux2 = {
                        'idArea': idArea,
                        'nome'  : nomeArea,
                        'datas' : resultadoDatas
                    }
                    retorno.push(aux2);
                    resolve(retorno);
                    
                });
            });
        }
        Promise.all([p]).then(retorno =>{
            if(retorno[0]){
                retorno[0].forEach(element => {
                    aux       = [];
                    var total = 0;
                    element.datas.forEach(elementDatas =>{
                        total += elementDatas.valor;
                    });
                    
                    aux.push(element.nome);
                    aux.push(total);
                    columns.push(aux);
                });
            }
            columns.sort(function(a, b) {
                return b[1] - a[1];
            });
    
            var legenda = '';
            for (let index = 0; index < columns.length; index++) {
                legenda += '<li><h6 class="text-muted " style="color:'+cores[index]+' !important"><i class="fa fa-circle font-10 m-r-10 "></i>'+columns[index][0]+'</h6> </li>';
            }
            $('#bodyGrafico'+id).empty().append('<div style="height:20%"><h3 class="card-title">'+titulo+'</h3>'+subTituloVerificado+'</div><div id="grafico'+id+'" style="height:70%; width:100%;"></div><div><hr class="m-t-0 m-b-0"></div><div class="text-center "><ul class="list-inline m-b-0" >'+legenda+'</ul></div>');
            
            var valores = {
                columns: columns,
                type : 'donut',
            };

            graficoPizza('#grafico'+id,valores,titulo);
        });   
    });
}

function montaGraficoTotal(id,config){
    var titulo    = config.tituloGrafico;
    var subTitulo = config.subTituloGrafico;
    var arrayTotal = [];
    
    var subTituloVerificado = '';
    $('#bodyGrafico'+id).attr('style','background-color:#26c6da;height:100%;width:98%;border-radius:5px');
    if(subTitulo) subTituloVerificado = '<h6 class="card-subtitle float-left">'+subTitulo+'</h6>';
    
    //Busca valores GED
    geradorParametros(config).then(params =>{
        let retorno = []
        for (let index = 0; index < params.length; index++) {
            p = new Promise((resolve, reject) => {
                let idArea  = params[index]['idArea'];
                let nomeArea= params[index]['nome'];
                let datas   = params[index]['datas'];
                let indices = params[index]['indices']; 
                montaConsultaGed(idArea,datas,indices).then(resultadoDatas =>{
                    let aux2 = {
                        'idArea': idArea,
                        'nome'  : nomeArea,
                        'datas' : resultadoDatas
                    }
                    retorno.push(aux2);
                    resolve(retorno); 
                });
            });
        }
        Promise.all([p]).then(retorno =>{
            if(retorno[0]){
                retorno[0].forEach(element => {
                    element.datas.forEach(elementDatas =>{
                        arrayTotal.push(elementDatas.valor);
                    });
                });
        
                var valores = arrayTotal;
                var total   = 0;
                valores.map(function (num){
                     total += num;
                });
            
                $('#bodyGrafico'+id).empty().append('<div class="d-flex" style="height: 20%"><div class="m-r-20 align-self-center"><h1 class="text-white"><i class="ti-bar-chart"></i></h1></div><div><h3 class="card-title">'+titulo+'</h3>'+subTituloVerificado+'</div></div><div class="row"><div class="col-4 align-self-center"><h2 class="font-light text-white float-left">'+total+'</h2></div><div class="col-8 p-t-10 p-b-20 text-right"><div  id="grafico'+id+'"  style="width:100%;height: 100%;"></div></div></div>');
                graficoTotal1('#grafico'+id,valores);
            }
        });
    });
}



function montaGraficoCustomizado(id, config){
    var titulo    = config.tituloGrafico;
    var subTitulo = config.subTituloGrafico;
    var arrayTotal = [];
    
    var subTituloVerificado = '';
    $('#bodyGrafico'+id).attr('style','height:100%;width:98%;border-radius:5px');
    if(subTitulo) subTituloVerificado = '<h6 class="card-subtitle float-left">'+subTitulo+'</h6>';
    
    
    //Busca valores GED
    geradorParametros(config).then(params => {
        let idAreas = [];
        let api = "";

        for (let index = 0; index < params.length; index++) {

            JSON.parse(params[index].idArea).forEach(area => {
                idAreas.push(area);
            });

            api = params[index]['api'];
        }

        let body = {
            'listaIdArea': idAreas,
            'listaIndice': [],
            'inicio': 0,
            'fim': 1000
        }

        $.ajax({
            type: "GET",
            url: api,
            dataType: "JSON",
            data: {
                token: $("#gedUserToken").val(),
                body: JSON.stringify(body),
                url: $("#gedUrl").val()
            },
            success: function (response) {
           
                let image = "data:image/jpeg;base64," + response.base64

                let width = parseInt($(".get-size-component-" + id).width()) - 30;
                let height = parseInt($(".get-size-component-" + id).height()) - 30;


                let component = "";
                
                component += '<div class="row">'
      
                component += '<div class="col-12">'
                
                component += '<div class="text-center" id="grafico'+id+'" style="max-width: ' + width + 'px; max-height: ' + height + 'px; -width: ' + width + 'px; height: ' + height + 'px;">'  
                
                component += '<img style="max-width: 100%; max-height: 100%" src=' + image + ' />'
                
                component += '</div>'
                
                component += '</div>'
                
                component += '</div>'
                
                
                $('#bodyGrafico'+id).empty().append(component)
                                        
            },error: function(e) {
                console.log(e)
            }
        }); 

    });
}
/* FIM Modelos de Graficos Disponiveis*/