<div class="row">
    <div class="col-2"></div>
    <div class="col-8">
        <body style="text-align: center;">
            <audio id="idAudio" src=""  autoplay="autoplay" controls="controls"></audio>
        </body>
    </div>
    <div class="col-2"></div>
</div>
@section('footer')
    <script>
        let arquivo  = "{{ $documento->bytes }}";
        let extensao = "audio/mp3";
        let podeBaixar   = "{{ $permissoes['usa_download'] }}";

        $('#idAudio').attr('src',"data:audio/mpeg;base64,"+arquivo).attr('type',extensao);
        if(!podeBaixar){
          $('#idVideo').attr('controlsList','nodownload');
        }
    </script>
@endsection