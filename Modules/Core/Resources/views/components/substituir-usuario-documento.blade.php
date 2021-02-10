<div class="row">
    <input type="hidden" id="idUsuario" name="idUsuario" value="{{$idUsuario}}">
    <div class="col-md-6">
        <div class="form-group required{{ $errors->has('grupo') ? ' has-error' : '' }}">
            {!! Form::label('grupo', 'Grupo', ['class' => 'control-label']) !!}
            {!! Form::select('grupo', $grupos, null, ['id' => 'grupo', 'class' => 'form-control selectpicker', 'data-live-search' => 'true', 'data-actions-box' => 'true', 'required' => 'required', 'placeholder' => __('components.selectepicker-default')]) !!}
            <small class="text-danger">{{ $errors->first('grupo') }}</small>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group required{{ $errors->has('documento') ? ' has-error' : '' }}">
            {!! Form::label('documento', 'Documentos', ['class' => 'control-label']) !!}
            {!! Form::select('documento[]', $documentos, null, ['id' => 'documento', 'class' => 'form-control selectpicker', 'data-live-search' => 'true', 'data-actions-box' => 'true','required' => 'required', 'multiple']) !!}
            <small class="text-danger">{{ $errors->first('documento') }}</small>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group required{{ $errors->has('usuario') ? ' has-error' : '' }}">
            {!! Form::label('usuario', 'Usuário Substituto',['class' => 'control-label']) !!}
            {!! Form::select('usuario',$usuariosSubstituto, null, ['id' => 'usuario', 'class' => 'form-control selectpicker','data-live-search' => 'true', 'data-actions-box' => 'true', 'required' => 'required', 'placeholder' => __('components.selectepicker-default')]) !!}
            <small class="text-danger">{{ $errors->first('usuario') }}</small>
        </div>
    </div>
</div>

@section('footer')
    <script>
        $(document).ready(function() {
            
            $('#grupo').on('change', function(){
                let grupo = $(this).val();
                let usuario = $('#idUsuario').val();
                let obj = {'grupo': grupo, 'usuario': usuario};
                $('#documento').empty();
                $('#usuario').empty();
                buscaUsuarios(grupo, usuario);

                ajaxMethod('POST', "{{ URL::route('docs.documento.documento-por-grupo') }}", obj).then(response => {
                    if(response.response == 'erro') {
                        swal2_alert_error_support("Tivemos um problema ao buscar os documentos por grupo.");
                    }else{
                        let array = JSON.parse(response.data);
                        let linha ='';
                        for (let index = 0; index < array.length; index++) {
                            const element = array[index];
                            linha += '<option value="'+element.id+'">'+element.codigo+'</option>'
                        }
                        $('#documento').append(linha).selectpicker('refresh');
                    }
                });
            });
        });

        function buscaUsuarios(grupo, usuario)
        {
            let obj = {'grupo': grupo, 'usuario': usuario};
            ajaxMethod('POST', "{{ URL::route('core.usuario.usuario-por-grupo') }}", obj).then(response => {
                if(response.response == 'erro') {
                    swal2_alert_error_support("Tivemos um problema ao buscar os usuários do grupo.");
                }else{
                    let array = JSON.parse(response.data);
                    let linha ='<option  value="">Nada selecionado</option>';
                    for (let index = 0; index < array.length; index++) {
                        const element = array[index];
                        console.log(element);
                        linha += '<option value="'+element.id+'">'+element.nome+'</option>'
                    }
                    $('#usuario').append(linha).selectpicker('refresh');
                   
                }
            });
        }

        function msgConfirmacao() {
            event.preventDefault();
            event.stopPropagation();
            let substituir = swal2_warning("O usuário será substituídos em "+$('#documento').val().length+" documentos.", "Sim, Substituir");
            substituir.then(resolvedValue => {
                document.formSubstituir.action = "{!! route('core.usuario.substituir-user-documento'); !!}";
                $('#formSubstituir').submit();
            }, error => {
                swal.close();
            });
        }
    </script>
@endsection