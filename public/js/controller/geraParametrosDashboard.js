var datas       = [];
/* Gerador de parametros */
function geradorParametros(config)
{   
    return new Promise((resolve,reject)=>{
        var arrayConsulta     = [];
        switch (config.periodoGrafico) {
            case '1':
                diaAtual();
                break;
            case '2':
                diaAnterior();
                break;
            case '3':
                semanaAnterior();
                break;
            case '4':
                mensal();
                break;
            case '5':
                mesAnterior();
                break;
            case '6':
                numMeses(2);
                break;
            case '7':
                numMeses(3);
                break;  
        }
        var i = 0;
        config.areaGrafico.forEach(element => {
            arrayConsulta[i] = {
                'idArea' : element.idArea,
                'nome'   : element.nome,
                'datas'  : datas,
                'indices': config.indiceValor,
                'api': config.caminhoApi
            };
            i++;
        });
        if(!arrayConsulta) reject(false);
        resolve(arrayConsulta);
    });
}