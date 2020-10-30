function montaConsultaGed(idArea,datas,indices)
{
    return new Promise((resolve,reject)=>{
        var resultadoDatas = [];
        
        
        getDatas(datas).then(async resp => {
            for (let index = 0; index < resp.length; index++) {
                var body        = [];
                let listaIndice = [];
                let possuiPesquisaPorData = false

                
                if(indices){
                    indices.forEach(elementIndices => {
                        let valor = elementIndices.valor
                        if (["5", "6"].includes(elementIndices.tipoIndice)) {
                            possuiPesquisaPorData = true
                            valor = moment(resp[index].dataInicial).format('DD/MM/YYYY')+";"+moment(resp[index].dataFinal).format('DD/MM/YYYY')
                        }
                        
                        listaIndice.push({
                            "idTipoIndice" : elementIndices.tipoIndice,
                            "identificador": elementIndices.indice,
                            "valor": valor
                        });

                    });
                }

                if (!possuiPesquisaPorData) {
                    listaIndice.push({
                        "idTipoIndice" : "6",
                        "identificador": "Data_do_registro",
                        "valor": moment(resp[index].dataInicial).format('DD/MM/YYYY')+";"+moment(resp[index].dataFinal).format('DD/MM/YYYY')
                    });
                }
                
                //monta body da requisição
                body = {
                    "listaIdArea": JSON.parse(idArea),
                    "listaIndice": listaIndice,
                    "inicio": 0,
                    "fim": 1000000,
                    "removido": false
                };
                await consulta(body).then(ret =>{
                    //monta resultado
                    var aux = { 
                        'dataInicial' : resp[index].dataInicial,
                        'dataFinal'   : resp[index].dataFinal,
                        'nome'        : resp[index].nome,
                        'valor'       : ret 
                    };
                    resultadoDatas.push(aux);
                }).catch(error_msg => {
                    console.log("#### ERROR NA CONSULTA AO GED! ####");
                    console.log("ERROR na consulta: "+ error_msg);
                });
            };
            resolve(resultadoDatas);
        });
    });
}

function getDatas(datas){
    return new Promise((resolve,reject)=>{
        resolve(datas);
    });   
}

function consulta(body)
{   
    return new Promise((resolve,reject)=>{
        $.ajax({
            url: '/ged/pesquisaRegistro',
            type: 'POST',
            data: {params: body},
            dataType: 'JSON',
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                resolve (data.error == false ? data.response.totalResultadoPesquisa: 0); 
            }
        });
    });  
}