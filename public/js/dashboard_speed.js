var chart = [];

// ============================================================== 
// Grafico de Linhas
// ============================================================== 
function graficoLinhas(seletor,valores){
    var chart1 = new Chartist.Line(seletor, valores, 
    {
        showArea: true,
        fullWidth: true,
        plugins: [
        Chartist.plugins.tooltip()
        ],
        axisY: {
            onlyInteger: true
            , scaleMinSpace: 40    
            , offset: 20
            , labelInterpolationFnc: function (value) {
                return value;
            }
        },
    });

    chart.push(chart1);
}

// ============================================================== 
// Grafico de barras
// ============================================================== 
function graficoBarras(seletor,valores){
    var chart2 = new Chartist.Bar(seletor, valores
    ,{
        axisX: {
            // On the x-axis start means top and end means bottom
            position: 'end',
            showGrid: false
        },
        axisY: {
            // On the y-axis start means left and end means right
            position: 'start'
        },
      
        plugins: [
            Chartist.plugins.tooltip()
        ]
    });
   chart.push(chart2);
}

// ============================================================== 
// Grafico de Pizza
// ============================================================== 
function graficoPizza(seletor, valores, titulo){
    var chart3 = c3.generate({
        bindto: seletor,
        data: valores,
        donut: {
            label: {
                show: false
                },
            title: titulo,
            width:20,
            
        },
        legend: {
            hide: true
            //or hide: 'data1'
            //or hide: ['data1', 'data2']
        },
        color: {
                pattern: ['#26c6da','#1e88e5','#f4c63d','#d17905','#453d3f','#59922b','#0544d3','#6b0392','#f05b4f','#dda458','#eacf7d','#86797d','#b2c326','#6188e2','#a748ca']
        }
    });
}


// ============================================================== 
// Grafico de Total 1 
// ============================================================== 
function graficoTotal1(seletor, valores){
    $(seletor).sparkline(valores, {
        type: 'bar'
        , width: '100%'
        , height: '70'
        , barWidth: '2'
        , resize: true
        , barSpacing: '6'
        , barColor: 'rgba(255, 255, 255, 0.3)'
    });
}

// ============================================================== 
// Grafico de Total 2 
// ============================================================== 
function graficoTotal2(seletor, valores){
    new Chartist.Line(seletor, valores
,   {
        showArea: true
        , fullWidth: true
        , plugins: [
        Chartist.plugins.tooltip()
        ], // As this is axis specific we need to tell Chartist to use whole numbers only on the concerned axis
        axisY: {
            onlyInteger: true
            , offset: 20
            , showLabel: false
            , showGrid: false
            , labelInterpolationFnc: function (value) {
                return value;
            }
        }
        , axisX: {
            showLabel: false
            , divisor: 1
            , showGrid: false
            , offset: 0
        }
    });
}



// ============================================================== 
// Animação dos Graficos
// ============================================================== 
function animacaoGrafico(){
    for (var i = 0; i < chart.length; i++) {
        chart[i].on('draw', function(data) {
            if (data.type === 'line' || data.type === 'area') {
                data.element.animate({
                    d: {
                        begin: 500 * data.index,
                        dur: 500,
                        from: data.path.clone().scale(1, 0).translate(0, data.chartRect.height()).stringify(),
                        to: data.path.clone().stringify(),
                        easing: Chartist.Svg.Easing.easeInOutElastic
                    }
                });
            }
            if (data.type === 'bar') {
                data.element.animate({
                    y2: {
                        dur: 500,
                        from: data.y1,
                        to: data.y2,
                        easing: Chartist.Svg.Easing.easeInOutElastic
                    },
                    opacity: {
                        dur: 500,
                        from: 0,
                        to: 1,
                        easing: Chartist.Svg.Easing.easeInOutElastic
                    }
                });
            }
        });
    }
}