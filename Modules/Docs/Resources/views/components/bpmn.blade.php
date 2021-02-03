
<div class="row">
    <div class="col-md-6">
        <div class="form-group required {{ $errors->has('nome') ? ' has-error' : '' }}">
            {!! Form::label('nome', 'Nome',['class' => 'control-label']) !!}
            {!! Form::text('nome', $nome, ['class' => 'form-control', 'required' => 'required']) !!}
            <small class="text-danger">{{ $errors->first('nome') }}</small>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group required{{ $errors->has('versao') ? ' has-error' : '' }}">
            {!! Form::label('versao', 'Versão',['class' => 'control-label']) !!}
            {!! Form::text('versao', $versao, ['class' => 'form-control', 'required' => 'required', 'readonly'=>'readonly']) !!}
            <small class="text-danger">{{ $errors->first('versao') }}</small>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        {!! Form::label('bpmn', 'BPMN 2.0',) !!}
        
        <iframe src="{{ url('plugins/bpmn/index.html') }}" name="iframeTeste" id="iframeTeste" class="resp-iframe"></iframe>
        <input type="hidden" id="arquivoXML2" name="arquivoXML2" value="{{$bpmnEdit->arquivo ?? ''}}">
    </div>
</div>
@section('footer')

<script>
    $(document).ready(function() {

        if($('#idBPMN').val() !== undefined)
        {
            let bpmn =  window.parent.document.getElementById('arquivoXML2').value;
            let encodedData = encodeURIComponent(bpmn);

            window.parent.iframeTeste.document.getElementById('js-drop-zone').classList.add('with-diagram');

            window.parent.iframeTeste.document.getElementById('js-download-diagram').classList.add('active');
            window.parent.iframeTeste.document.getElementById('js-download-diagram').href = 'data:application/bpmn20-xml;charset=UTF-8,' + encodedData;
            window.parent.iframeTeste.document.getElementById('js-download-diagram').download = 'diagram.bpmn'

            window.parent.iframeTeste.document.getElementById('js-download-svg').classList.add('active');
            window.parent.iframeTeste.document.getElementById('js-download-svg').href = 'data:application/bpmn20-xml;charset=UTF-8,' + encodedData;
            window.parent.iframeTeste.document.getElementById('js-download-svg').download = 'diagram.svg'

            window.parent.iframeTeste.document.getElementById('arquivoXML').value = bpmn;

            
        }

    });

    $(document).on("click","#btn-bpmn", function() {
        let valorIframe = '';
        valorIframe = window.parent.iframeTeste.document.getElementById('arquivoXML').value;

        if(valorIframe == '' || $('#nome').val() == ''){
            swal2_alert_error_not_reload("Favor preencher o nome e desenhar algum BPMN.");
        }else{
            $('#arquivoXML2').val(valorIframe);
            $('#formBpmn').submit();
        }
    });
</script>

@endsection