<div class="form-body">
    <div class="row p-t-20">
        <div class="col-md-6">
            <div class="form-group required{{ $errors->has('nome') ? ' has-error' : '' }}">
                {!! Form::label('nome', 'Nome', ['class' => 'control-label']) !!}
                {!! Form::text('nome', $nome, ['class' => 'form-control', 'required' => 'required']) !!}
                <small class="text-danger">{{ $errors->first('nome') }}</small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group required{{ $errors->has('sigla') ? ' has-error' : '' }}">
                {!! Form::label('sigla', 'Sigla', ['class' => 'control-label']) !!}
                {!! Form::text('sigla', $sigla, ['class' => 'form-control', 'required' => 'required']) !!}
                <small class="text-danger">{{ $errors->first('sigla') }}</small>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group required{{ $errors->has('descricao') ? ' has-error' : '' }}">
                {!! Form::label('descricao', 'Descrição', ['class' => 'control-label']) !!}
                {!! Form::text('descricao', $descricao, ['class' => 'form-control', 'required' => 'required']) !!}
                <small class="text-danger">{{ $errors->first('descricao') }}</small>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group required{{ $errors->has('codigoPadrao') ? ' has-error' : '' }}">
                
                {!! Form::label('codigoPadrao', 'Padrão de Código', ['class' => 'control-label']) !!}
                
                {!! Form::select('codigoPadrao[]', $padroesCodigo, !empty($tipoDocumentoEdit) ?  json_decode($tipoDocumentoEdit->codigo_padrao) : null, ['id' => 'codigoPadrao', 'class' => 'form-control ', 'required' => 'required', 'multiple' ]) !!}
                <small class="text-danger">{{ $errors->first('codigoPadrao') }}</small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group required{{ $errors->has('ultimoDocumento') ? ' has-error' : '' }}">
                {!! Form::label('ultimoDocumento', 'Último Documento', ['class' => 'control-label']) !!}
                {!! Form::number('ultimoDocumento',$ultimoDocumento, ['class' => 'form-control', 'required' => 'required', 'min' => 0]) !!}
                <small class="text-danger">{{ $errors->first('ultimoDocumento') }}</small>
            </div>
        </div>
        
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group required{{ $errors->has('numeroPadrao') ? ' has-error' : '' }}">
                 {!! Form::label('numeroPadrao', 'Padrão de Número' , ['class' => 'control-label']) !!}
                 {!! Form::select('numeroPadrao',$padroesNumero, !empty($tipoDocumentoEdit) ?  $tipoDocumentoEdit->numero_padrao_id : null, ['id' => 'numeroPadrao', 'class' => 'form-control', 'required' => 'required', 'placeholder' => __('components.selectepicker-default')]) !!}
                 <small class="text-danger">{{ $errors->first('numeroPadrao') }}</small>
            </div>
        </div>
        <div class="col-md-6">
            <label></label>
            <div class="alert alert-info">
                <span><b>Exemplos de valores aceitos:</b></span>
                <ul>
                    <li>0       <span class="text-muted">- Código gerado será [1, 2, 3...]</span></li>
                    <li>00      <span class="text-muted">- Código gerado será [01, 02, 03...]</span></li>
                    <li>000     <span class="text-muted">- Código gerado será [001, 002, 003...]</span></li>
                    <li>0000    <span class="text-muted">- Código gerado será [0001, 0002, 0003...]</span></li>
                </ul>
                <small><b>Lembre-se:</b> são aceitos apenas 4 dígitos!</small>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group{{ $errors->has('periodoVigencia') ? ' has-error' : '' }}">
                {!! Form::label('periodoVigencia', 'Perído de Vigência (Meses)') !!}
                {!! Form::number('periodoVigencia',$periodosVigencia, ['class' => 'form-control', 'required' => 'required', 'min' => 0]) !!}
                <small class="text-danger">{{ $errors->first('periodoVigencia') }}</small>
            </div>
            
        </div>
        <div class="col-md-6">
            <div class="form-group{{ $errors->has('periodoAviso') ? ' has-error' : '' }}">
            {!! Form::label('periodoAviso', 'Período para Aviso de Vencimento (Dias)') !!}
            {!! Form::number('periodoAviso',$periodosAviso, ['class' => 'form-control', 'required' => 'required', 'min' => 0]) !!}
            <small class="text-danger">{{ $errors->first('periodoAviso') }}</small>
            </div>
        </div>
    </div>
    <div class="row">
        
        <div class="col-md-6">
            <div class="form-group{{ $errors->has('tipoDocumentoPai') ? ' has-error' : '' }}" id="divTipoDocumentoPai">
                {!! Form::label('tipoDocumentoPai', 'Tipo Documento Pai', ['id'=> 'labelTipoDocumentoPai']) !!}
                {!! Form::select('tipoDocumentoPai',$tiposDocumento, !empty($tipoDocumentoEdit) ?  $tipoDocumentoEdit->tipo_documento_pai_id : null, ['id' => 'tipoDocumentoPai', 'class' => 'form-control selectpicker', 'placeholder' => __('components.selectepicker-default')]) !!}
                <small class="text-danger">{{ $errors->first('tipoDocumentoPai') }}</small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <div class="checkbox{{ $errors->has('vinculoObrigatorio') ? ' has-error' : '' }}" id="divVinculoObrigatorio">
                    <label class="control-label" id="labelVinculoObrigatorio">Vínculo Obrigatório ao Documento Pai</label>
                                    
                    <td class="text-center text-nowrap">
                        <div class="switch">
                            <label>Não
                                {!! Form::checkbox('vinculoObrigatorio', '1', !empty($tipoDocumentoEdit) ?  $tipoDocumentoEdit->vinculo_obrigatorio : false, ['id' => 'vinculoObrigatorio', 'class'=> 'switch-elaborador', 'disabled' => true]) !!}<span class="lever switch-col-light-blue"></span>Sim
                            </label>
                        </div>
                    </td>
                <small class="text-danger">{{ $errors->first('vinculoObrigatorio') }}</small>
                </div>
            </div>
        </div>
    </div>
    <div class="row">    
        <div class="col-md-12">
            <div class="form-group required{{ $errors->has('documentoModelo') ? ' has-error' : '' }}">
                {!! Form::label('documentoModelo', 'Modelo de Documento', ['class' => 'control-label']) !!}<br>
                {!! Form::file('documentoModelo', [empty($tipoDocumentoEdit) ? 'required': '', 'accept' => "$extensoesDocumentos", 'class' => 'dropify', 'id' => 'input-file-now']) !!}

                <small class="text-danger">{{ $errors->first('documentoModelo') }}</small>
            </div>
        </div>
        
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group required{{ $errors->has('fluxo') ? ' has-error' : '' }}">
            {!! Form::label('fluxo', 'Fluxo' , ['class' => 'control-label']) !!}
            {!! Form::select('fluxo',$fluxos, !empty($tipoDocumentoEdit) ?  $tipoDocumentoEdit->fluxo_id : null, ['id' => 'fluxo', 'class' => 'form-control selectpicker', 'required' => 'required', 'placeholder' => __('components.selectepicker-default')]) !!}
            <small class="text-danger">{{ $errors->first('fluxo') }}</small>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <div class="checkbox{{ $errors->has('ativo') ? ' has-error' : '' }}">
                    <label class="control-label">Status</label>
                    <td class="text-center text-nowrap">
                        <div class="switch">
                            <label for="ativo">Inativo
                            {!! Form::checkbox('ativo', '1', !empty($tipoDocumentoEdit) ?  $tipoDocumentoEdit->ativo : true, ['id' => 'ativo', 'class'=> 'switch-elaborador']) !!}
                            <span class="lever switch-col-light-blue"></span>Ativo
                            </label>
                        </div>
                    </td>    
                </div>
                <small class="text-danger">{{ $errors->first('ativo') }}</small>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="form-group">
                <div class="checkbox{{ $errors->has('permitirDownload') ? ' has-error' : '' }}">
                    <label class="control-label">Permitir Download</label>
                                    
                    <td class="text-center text-nowrap">
                        <div class="switch">
                            <label>Não
                                {!! Form::checkbox('permitirDownload', '1', !empty($tipoDocumentoEdit) ?  $tipoDocumentoEdit->permitir_download : true, ['id' => 'permitirDownload', 'class'=> 'switch-elaborador']) !!}<span class="lever switch-col-light-blue"></span>Sim
                            </label>
                        </div>
                    </td>
                <small class="text-danger">{{ $errors->first('permitirDownload') }}</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <div class="checkbox{{ $errors->has('permitirImpressao') ? ' has-error' : '' }}">
                    <label class="control-label">Permitir Impressão</label>
                                    
                    <td class="text-center text-nowrap">
                        <div class="switch">
                            <label>Não
                                {!! Form::checkbox('permitirImpressao', '1', !empty($tipoDocumentoEdit) ?  $tipoDocumentoEdit->permitir_impressao : true, ['id' => 'permitirImpressao', 'class'=> 'switch-elaborador']) !!}<span class="lever switch-col-light-blue"></span>Sim
                            </label>
                        </div>
                    </td>
                <small class="text-danger">{{ $errors->first('permitirImpressao') }}</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <div class="checkbox{{ $errors->has('vinculoObrigatorioOutrosDocs') ? ' has-error' : '' }}">
                    <label class="control-label">Vínculo Obrig. a Outros Doc.</label>
                                    
                    <td class="text-center text-nowrap">
                        <div class="switch">
                            <label>Não
                                {!! Form::checkbox('vinculoObrigatorioOutrosDocs', '1', !empty($tipoDocumentoEdit) ?  $tipoDocumentoEdit->vinculo_obrigatorio_outros_documento : false, ['id' => 'permitirImpressao', 'class'=> 'switch-elaborador']) !!}<span class="lever switch-col-light-blue"></span>Sim
                            </label>
                        </div>
                    </td>
                <small class="text-danger">{{ $errors->first('vinculoObrigatorioOutrosDocs') }}</small>
                </div>
            </div>
        </div>     
    </div>
    <div class="row">

    </div>
    
</div>

@section('footer')
<link href="{{ asset('plugins/select2/dist/css/select2.min.css') }}" rel="stylesheet" type="text/css">    
<script src="{{ asset('plugins/select2/dist/js/select2.full.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('plugins/jqueryui/jquery-ui.min.js') }}"></script>
<link rel="stylesheet" href="{{ asset('plugins/dropify/dist/css/dropify.min.css') }}">
<script src="{{ asset('plugins/dropify/dist/js/dropify.min.js') }}"></script>
<script>
    $(document).ready(function(){
        verificaVinculoObrigatorio();   

        $("#codigoPadrao").select2({
            placeholder: 'Selecione o padrão de codigo'
        }).on("select2:select", function (evt) {
                var id = evt.params.data.id;

                var element = $(this).children("option[value="+id+"]");

                moveElementToEndOfParent(element);

                $(this).trigger("change");
            });
        var ele = $("#codigoPadrao").parent().find("ul.select2-selection__rendered");
        ele.sortable({
            containment: 'parent',
            update: function() {
                orderSortedValues();
                console.log(""+$("#codigoPadrao").val())
            }
        });

        orderSortedValues = function() {
        var value = ''
        $("#codigoPadrao").parent().find("ul.select2-selection__rendered").children("li[title]").each(function(i, obj){
                var element = $("#codigoPadrao").children('option').filter(function () { return $(this).html() == obj.title });
                moveElementToEndOfParent(element)
            });
        };

        moveElementToEndOfParent = function(element) {
            var parent = element.parent();
            element.detach();
            parent.append(element);
        };
        

        // Basic
        $('.dropify').dropify();

        // Translated
        $('.dropify-fr').dropify({
            messages: {
                default: 'Glissez-déposez un fichier ici ou cliquez',
                replace: 'Glissez-déposez un fichier ou cliquez pour remplacer',
                remove: 'Supprimer',
                error: 'Désolé, le fichier trop volumineux'
            }
        });

        // Used events
        var drEvent = $('#input-file-events').dropify();

        drEvent.on('dropify.beforeClear', function(event, element) {
            return confirm("Do you really want to delete \"" + element.file.name + "\" ?");
        });

        drEvent.on('dropify.afterClear', function(event, element) {
            alert('File deleted');
        });

        drEvent.on('dropify.errors', function(event, element) {
            console.log('Has Errors');
        });

        var drDestroy = $('#input-file-to-destroy').dropify();
        drDestroy = drDestroy.data('dropify')
        $('#toggleDropify').on('click', function(e) {
            e.preventDefault();
            if (drDestroy.isDropified()) {
                drDestroy.destroy();
            } else {
                drDestroy.init();
            }
        })

        $('#tipoDocumentoPai').on('change', function(){
            verificaVinculoObrigatorio();   
        });
    });

    function verificaVinculoObrigatorio(){

        if( $('#tipoDocumentoPai').val() != '' ){
            $('#vinculoObrigatorio').removeAttr('disabled');
            $('#divVinculoObrigatorio').attr('class', 'form-group required');
            $('#labelVinculoObrigatorio').attr('class', 'control-label');
        }else{
            $('#vinculoObrigatorio').attr('disabled', true).prop('checked', false);
            $('#labelVinculoObrigatorio').removeAttr('class');
            $('#divTipoDocumentoPai').attr('class', 'form-group');
        }
    }

</script>
@endsection