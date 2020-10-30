<div class="col-md-12 alert alert-info">
    Clique sobre a imagem para abrir o <span class="font-weight-bold" style="color: cornflowerblue">modal de visualização</span>
</div>

<div class="col-10 text-center">
    <img id="image" src="" alt="Documento" style="cursor:pointer">
</div>
@section('footer')
    
<link  href="{{ asset('css/viewer.css') }}" rel="stylesheet">
<script src="{{ asset('js/viewer.min.js') }}"></script>
<script src="{{ asset('js/jquery.viewer.js') }}"></script>

<script>
    let arquivo  = "{{ $documento->bytes }}";
    let extensao = "image/png";
    $('#image').attr('src',"data:image/png;base64,"+arquivo).attr('type',extensao);

    var $image = $('#image');

    $image.viewer({
        modal: true,
        viewed: function() {
            $image.viewer('zoomTo', 1);
        }
    });

    // // Get the Viewer.js instance after initialized
    // var viewer = $image.data('viewer');

    // // View a list of images
    // $('#images').viewer();
</script>
@endsection