<div class="row">
    <div class="col-md-12">
        <div class="iframe_box">
            @php
                $action = $permissoes['usa_editar'] ? 'edit' : 'view';
            @endphp
            <iframe src='{{ asset("plugins/onlyoffice-php/doceditor.php?type=desktop&fileID=" . $documento->endereco . "&action=" . $action) }}' style="width:100%; min-height:800px;" frameborder="0"></iframe>
        </div>
    </div>
</div>
@if ($permissoes['usa_editar'] && (empty($documento->status) && file_exists(public_path('plugins/onlyoffice-php/Storage') . "/" . $documento->endereco)))
    
        <form method="POST" id="updateDocumento" name="updateDocumento" action="{{ route('processo.documento.update') }}">
            <div class="row">
                {{ csrf_field() }}
                <input type="hidden" name="documento" id="documeto" value="{{ json_encode($documento) }}">
                <input type="hidden" name="endereco" id="endereco" value="{{ $documento->endereco }}">
                <input type="hidden" name="path" id="path" value="{{ public_path('plugins/onlyoffice-php/Storage') . "/" . $documento->endereco }}">
                <div class="col-md-12 mt-2">
                    <div class="form-actions  pull-right">
                        <button class="btn btn-success btn-lg " type="button" id="saveNewDoc"><i class="fa fa-check"></i> @lang('buttons.general.save')</button>
                        <button class="btn btn-secondary btn-lg " type="button" id="cancelEditDoc"> @lang('buttons.general.cancelEdit')</button> 
                    </div>    
                </div>
            </div>    
        </form>    
    
@endif

@section('footer')
    <!-- SweetAlert2 -->
    <script>
        $('#saveNewDoc').click(function(){
            swal2_warning("Essa ação irá salvar outra versão do documento !", "Sim, salvar!").then(resolvedValue => {
                $('#updateDocumento').submit();
            }, error => {
                swal.close();
            }); 
        });

        
        $('#cancelEditDoc').click(function(){
            swal2_warning("Essa ação irá cancelar a edição do documento !", "Sim, cancelar!").then(resolvedValue => {
                
                $.ajax({
                    url: '/edicaoDocumento/deleteRegistroAndDoc',
                    type: 'POST',
                    data: {endereco: $('#endereco').val(), path: $('#path').val() },
                    dataType: 'JSON',
                    headers: {
                        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (data) {
                        if(data){
                            swal2_success("Cancelado!", "Edição cancelada com sucesso.");
                            window.history.back();
                        }else{
                            swal2_alert_error_support("Tivemos um problema ao cancelar a edição do documento.");
                        }
                    }
                });

            }, error => {
                swal.close();
            });
        });
        
    </script>
@endsection