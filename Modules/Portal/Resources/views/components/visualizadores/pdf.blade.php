<div class="row">
    <div class="col-1"></div>
    <div class="col-10">
        <iframe id="pdf-view" src="{{ asset('plugins/pdfjs/web/viewer.html') }}" style="width: inherit; min-height: 900px; visibility: hidden" allowfullscreen="" webkitallowfullscreen=""></iframe>
    </div>
</div>
@section('footer')
    {{-- pdf.js --}}
    <script>
    $(document). ready(function() {
        let iframe       = document.getElementById('pdf-view');
        let pdfRAW       = "{{ $documento->bytes }}";
        let podeBaixar   = "{{ $permissoes['usa_download'] }}";
        let podeImprimir = "{{ $permissoes['usa_imprimir'] }}";

        //Convertendo base64 para um array de bytes
        var base64ToUint8Array = function(base64){
            var raw = atob(base64);
            var uint8Array = new Uint8Array(raw.length);
            for (var i = 0; i < raw.length; i++) {
                uint8Array[i] = raw.charCodeAt(i);
            }
            return uint8Array;
        };

        let pdfData = base64ToUint8Array(pdfRAW);
        
        // TimeOut necessário pois, quando a página acaba de ser carregada, o documento original é carregado. Esse é o tempo necessário para renderizar o pdf do GED.
        setTimeout(function(){
            iframe.contentWindow.PDFViewerApplication.open(pdfData);
            iframe.style.visibility = "visible";

            const frameContent = document.getElementById("pdf-view").contentDocument;
            const btnDownload  = frameContent.getElementById('download');
            const btnImprimir  = frameContent.getElementById('print');
            if( podeBaixar != true ) btnDownload.remove();
            if( podeImprimir != true ) btnImprimir.remove();
            
        }, 1500);
    });
    </script>
@endsection