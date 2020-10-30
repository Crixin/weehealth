moment.locale('pt-BR');
moment.updateLocale('pt', {
    weekdays : ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex","Sab"],
    months : ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez"]  
});
/*Função De Datas */
function diaAtual()
{
    datas[0] = {
        'dataInicial' : moment().format("YYYY-MM-DD"),
        'dataFinal'   : moment().format("YYYY-MM-DD"),
        'nome'        : 'Hoje'
    };
}

function diaAnterior()
{
    datas[0] = {
        'dataInicial' : moment().subtract(1,'d').format('YYYY-MM-DD'),
        'dataFinal'   : moment().subtract(1,'d').format('YYYY-MM-DD'),
        'nome'        : 'Ontem'
    };
}

function semanaAnterior()
{
    var ultimoDomingo = moment().day(0).format('YYYY-MM-DD');
    var i = 0;
    for (let index = 7; index > 0; index--) {
        var dia = moment(ultimoDomingo).subtract(index,'d').format('YYYY-MM-DD');
        datas[i] = {
            'dataInicial' : moment(ultimoDomingo).subtract(index,'d').format('YYYY-MM-DD'),
            'dataFinal'   : moment(ultimoDomingo).subtract(index,'d').format('YYYY-MM-DD'),
            'nome'        : moment(dia).format('dddd')
        }
        i++;  
    }
}

function mensal()
{
    var dataInicial = moment().format('YYYY-MM-01');
    var dataFinal   = moment(dataInicial).endOf('month').format('YYYY-MM-DD');
    var duraticao   = moment.duration(moment(dataFinal).diff(moment(dataInicial)));
    var diasMes     = duraticao["_data"]["days"];
    var numSemanas  = (diasMes/7) <= 4 ? 4 : 5; 

    for (let index = 0; index < numSemanas; index++) {
        
        datas[index] = {
            'dataInicial' : dataInicial,
            'dataFinal'   : (index <= 3) ? moment(dataInicial).add(7,'d').format('YYYY-MM-DD') : moment(dataInicial).endOf('month').format('YYYY-MM-DD'),
            'nome'        : Number(index+1)+'° '+'Sem.'+' '+moment(dataInicial).format('MMMM-YYYY')
        };
        dataFinal = moment(dataInicial).add(7,'d').format('YYYY-MM-DD');
        dataInicial = dataFinal;
    }

}

function mesAnterior()
{
    var dataInicial = moment().format('YYYY-MM-01');
    var dataInicialMesAnterior = moment(dataInicial).subtract(1,'M').format('YYYY-MM-DD');
    var dataFinalesAnterior    = moment(dataInicialMesAnterior).endOf('month').format('YYYY-MM-DD');
    var duraticao   = moment.duration(moment(dataFinalesAnterior).diff(moment(dataInicialMesAnterior)));
    var diasMes     = duraticao["_data"]["days"];
    var numSemanas  = (diasMes/7) <= 4 ? 4 : 5;

    for (let index = 0; index < numSemanas; index++) {
        datas[index] = {
            'dataInicial' : dataInicialMesAnterior,
            'dataFinal'   : (index <= 3) ? moment(dataInicialMesAnterior).add(7,'d').format('YYYY-MM-DD') : moment(dataInicialMesAnterior).endOf('month').format('YYYY-MM-DD'),
            'nome'        : Number(index+1)+'° '+'Sem.'+' '+moment(dataInicialMesAnterior).format('MMMM-YYYY') 
        };
        dataFinal = moment(dataInicialMesAnterior).add(7,'d').format('YYYY-MM-DD');
        dataInicialMesAnterior = dataFinal;  
    }
}

function numMeses(meses)
{
    var dataInicial = moment().format('YYYY-MM-01');
    
    for (let index = 0; index < meses; index++) {
        var dataInicialMes = moment(dataInicial).subtract((meses-1)-index,'M').format('YYYY-MM-DD');
        var dataFinalMes = moment(dataInicialMes).endOf('month').format('YYYY-MM-DD');
        datas[index] = {
            'dataInicial' : dataInicialMes,
            'dataFinal'   : dataFinalMes,
            'nome'        : moment(dataInicialMes).format('MMMM-YYYY')
        }
    }
}
/*FIM Função De Datas */