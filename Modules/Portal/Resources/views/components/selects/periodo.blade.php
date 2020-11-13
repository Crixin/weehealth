<div class="col-md-{{$size}}">
    <label class="control-label">Período</label>
    <select class="form-control selectpicker" data-size="10" data-live-search="true" data-actions-box="true" name="periodo" id="periodo" {{$required ?? ''}}>
        <option value="">Selecione</option>
        <option value="{{date('d/m/Y')}}">Hoje</option>
        <option value="{{date('d/m/Y', strtotime('first day of 0 month'))}}">Este mês</option>
        <option value="{{date('d/m/Y', strtotime('first day of -1 month')) . "-" . date('d/m/Y', strtotime('last day of -1 month'))}}">Mês passado</option>
        <option value="definir">Definir período</option>
    </select>
</div>

<div class="col-md-{{$size}} d-none datas-periodo">
    <label class="control-label">Data inicial</label>
    <input class="form-control" type="date" name="dataInicial" id="dataInicial" value="{{ old('dataInicial') ?? $dataInicialSelecionada ?? '' }}" >
</div>

<div class="col-md-{{$size}} d-none datas-periodo">
    <label class="control-label">Data final</label>
    <input class="form-control" type="date" name="dataFinal" id="dataFinal" value="{{ old('dataFinal') ?? $dataFinalSelecionada ?? '' }}" >
</div>
<script>
    
    let valueOld = "{{old('periodo')}}";
    let periodoSelecionado = "{{$periodoSelecionado ?? ''}}";
    
    document.getElementById('periodo').value= valueOld || periodoSelecionado;
    
    function changePeriodo()
    {   
        if (document.getElementById("periodo").value == "definir" ||  valueOld == "definir" || periodoSelecionado == "definir" ) {
            document.getElementsByClassName('datas-periodo')[0].classList.remove("d-none");
            document.getElementsByClassName('datas-periodo')[1].classList.remove("d-none");
        }else{
            document.getElementsByClassName('datas-periodo')[0].classList.add("d-none");
            document.getElementsByClassName('datas-periodo')[1].classList.add("d-none");
            document.getElementById("dataInicial").value = "";
            document.getElementById("dataFinal").value = "";
        }
    }

    changePeriodo();

    document.getElementById("periodo").addEventListener("change", function(e) {
        valueOld = "";
        periodoSelecionado = "";
        changePeriodo();
    });

</script>
