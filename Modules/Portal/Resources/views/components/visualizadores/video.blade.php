<div class="row">
    <div class="col-2"></div>
    <div class="col-8">
        <body>
          <video id="idVideo" src="" controls  width="640" height="264">
            <p class="vjs-no-js">Para ver este vídeo, ative o JavaScript e considere atualizar para um navegador que suporta vídeo HTML5</a></p>  
          </video>
        </body>
    </div>
    <div class="col-2"></div>
</div>
@section('footer')
    
    <script>
        let arquivo  = "{{ $documento->bytes }}";
        let extensao = "video/mp4";
        let podeBaixar   = "{{ $permissoes['usa_download'] }}";

        $('#idVideo').attr('src',"data:video/mp4;base64,"+arquivo).attr('type',extensao);
        if(!podeBaixar){
          $('#idVideo').attr('controlsList','nodownload');
        }
    </script>
@endsection