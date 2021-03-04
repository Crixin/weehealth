<div class="form-body">
    <div class="row p-t-20">
        <div class="col-md-6">
            <div class="form-group{{ $errors->has('codigo') ? ' has-error' : '' }}">
                {!! Form::label('codigo', 'Código') !!}
                {!! Form::text('codigo', $codigo, ['class' => 'form-control', 'disabled' => true]) !!}
                <small class="text-danger">{{ $errors->first('codigo') }}</small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group{{ $errors->has('validade') ? ' has-error' : '' }}">
                {!! Form::label('validade', 'Validade') !!}
                {!! Form::date('validade',$validade, ['class' => 'form-control', 'disabled'=>true]) !!}
                <small class="text-danger">{{ $errors->first('validade') }}</small>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group required{{ $errors->has('tituloDocumento') ? ' has-error' : '' }}">
            {!! Form::label('tituloDocumento', 'Título do Documento', ['class' => 'control-label']) !!}
            {!! Form::text('tituloDocumento', $tituloDocumento, ['class' => 'form-control', 'required' => 'required']) !!}
            <small class="text-danger">{{ $errors->first('tituloDocumento') }}</small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group required{{ $errors->has('tipoDocumento') ? ' has-error' : '' }}">
                {!! Form::label('tipoDocumento', 'Tipo de Documento' , ['class' => 'control-label']) !!}
                {!! Form::select('tipoDocumento',$tiposDocumento, !empty($documentoEdit) ?  $documentoEdit->tipo_documento_id : null, ['id' => 'tipoDocumento', 'class' => 'form-control selectpicker', 'required' => 'required','data-live-search' => 'true', 'data-actions-box' =>'true', 'placeholder' => __('components.selectepicker-default')]) !!}
                <small class="text-danger">{{ $errors->first('tipoDocumento') }}</small>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group required{{ $errors->has('setor') ? ' has-error' : '' }}">
                {!! Form::label('setor', 'Setor', ['class' => 'control-label']) !!}
                {!! Form::select('setor',$setores, !empty($documentoEdit) ?  $documentoEdit->setor_id : null, ['id' => 'setor', 'class' => 'form-control selectpicker', 'required' => 'required','data-live-search' => 'true', 'data-actions-box' =>'true', 'placeholder' => __('components.selectepicker-default')]) !!}
                <small class="text-danger">{{ $errors->first('setor') }}</small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group{{ $errors->has('documentoPai') ? ' has-error' : '' }}" id="divDocumentoPai">
                {!! Form::label('documentoPai', 'Documento(s) Pai', ['id' => 'labelDocumentoPai']) !!}
                {!! Form::select('documentoPai',$documentosPais, null, ['id' => 'documentoPai', 'name'=>'documentoPai[]', 'class' => 'form-control selectpicker', 'multiple', 'data-live-search' => 'true', 'data-actions-box' =>'true']) !!}
                <small class="text-danger">{{ $errors->first('documentoPai') }}</small>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group required{{ $errors->has('nivelAcesso') ? ' has-error' : '' }}">
                {!! Form::label('nivelAcesso', 'Nível de Acesso', ['class' => 'control-label']) !!}
                {!! Form::select('nivelAcesso',$niveisAcesso, !empty($documentoEdit) ?  $documentoEdit->nivel_acesso_id : null, ['id' => 'nivelAcesso', 'class' => 'form-control selectpicker', 'required' => 'required', 'data-live-search' => 'true', 'data-actions-box' =>'true', 'placeholder' => __('components.selectepicker-default')]) !!}
                <small class="text-danger">{{ $errors->first('nivelAcesso') }}</small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group {{ $errors->has('classificacao') ? ' has-error' : '' }}">
                {!! Form::label('classificacao', 'Classificação') !!}
                {!! Form::select('classificacao',$classificacoes, !empty($documentoEdit) ?  $documentoEdit->classificacao_id : null, ['id' => 'classificacao', 'class' => 'form-control selectpicker', 'data-live-search' => 'true', 'data-actions-box' =>'true', 'placeholder' => __('components.selectepicker-default')]) !!}
                <small class="text-danger">{{ $errors->first('classificacao') }}</small>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group{{ $errors->has('documentoVinculado') ? ' has-error' : '' }}" id="divDocumentosVinculados">
                {!! Form::label('documentoVinculado', 'Documento(s) Vinculado(s)', ["id" =>'labelDocumentosVinculados']) !!}
                {!! Form::select('documentoVinculado',$documentosVinvulados, !empty($documentoEdit) ?  $documentosVinculadosSelecionados : null, ['id' => 'documentoVinculado', 'name'=>'documentoVinculado[]', 'class' => 'form-control selectpicker', 'multiple', 'data-live-search' => 'true', 'data-actions-box' =>'true']) !!}
                <small class="text-danger">{{ $errors->first('documentoVinculado') }}</small>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <div class="checkbox required{{ $errors->has('copiaControlada') ? ' has-error' : '' }}">
                    {!! Form::label('copiaControlada', 'Copia Controlada', ['class' => 'control-label']) !!}
                    <td class="text-center text-nowrap">
                        <div class="switch">
                            <label for="copiaControlada">Não
                                {!! Form::checkbox('copiaControlada', '1', !empty($documentoEdit) ?  $documentoEdit->copia_controlada : false, ['id' => 'copiaControlada', 'class'=> 'switch-elaborador']) !!}
                                <span class="lever switch-col-light-blue"></span>Sim
                            </label>
                            
                        </div>
                        @if ( !empty($documentoEdit) && $documentoEdit->copia_controlada)
                            <button type="button" id="btnGerenciarCopiaControlada" class="btn btn-success pull-right">Gerenciar</button>
                        @endif
                    </td>    
                </div>
                
                <small class="text-danger">{{ $errors->first('copiaControlada') }}</small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <div class="checkbox required{{ $errors->has('obsoleto') ? ' has-error' : '' }}">
                    {!! Form::label('obsoleto', 'Obsoleto', ['class' => 'control-label']) !!}
                    <td class="text-center text-nowrap">
                        <div class="switch">
                            <label for="obsoleto">Não
                                {!! Form::checkbox('obsoleto', '1', !empty($normaEdit) ?  $normaEdit->obsoleto : false, ['id' => 'obsoleto', 'class'=> 'switch-elaborador']) !!}
                                <span class="lever switch-col-light-blue"></span>Sim
                            </label>
                        </div>
                    </td>    
                </div>
                <small class="text-danger">{{ $errors->first('obsoleto') }}</small>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group{{ $errors->has('bpmn') ? ' has-error' : '' }}">
                {!! Form::label('bpmn', 'BPMN 2.0') !!}
                {!! Form::select('bpmn',$bpmns, !empty($documentoEdit) ?  $documentoEdit->bpmn_id : null, ['id' => 'bpmn', 'class' => 'form-control selectpicker', 'data-live-search' => 'true', 'data-actions-box' =>'true','placeholder' => __('components.selectepicker-default')]) !!}
                <small class="text-danger">{{ $errors->first('bpmn') }}</small>
            </div>
        </div>
    </div>
    <legend>Aprovadores</legend>
    <hr>
    <div class="row aprovadores" >
        <div class="col-md-12 mb-3">
            <b>Selecione um tipo de documento</b>
        </div>
    </div>
    <legend>Grupos</legend>
    <hr>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <div class="col-md-10 control-label font-bold">
                    {!! Form::label('grupoTreinamentoDoc', 'GRUPO DE TREINAMENTO') !!}
                </div>
                <div class="col-md-12">
                    <select multiple class="optgroup" id="optgroup-newGrupoTreinamentoDoc" name="grupoTreinamentoDoc[]">
                        @foreach($gruposUsuarios as $key => $grupo)
                            <optgroup label="{{ $key }}">
                                @foreach($grupo as $key2 => $user)
                                    <option value="{{ $key2 }}" @if (in_array($key2, $grupoTreinamentoSelecionado)) selected="selected"@endif >{{ $user }}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>
            </div>   
        </div>       
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <div class="col-md-10 control-label font-bold">
                    {!! Form::label('grupoDivulgacaoDoc', 'GRUPO DE DIVULGAÇÃO') !!}
                </div>
                <div class="col-md-12">
                    <select multiple class="optgroup" id="optgroup-newGrupoDivulgacaoDoc" name="grupoDivulgacaoDoc[]">
                        @foreach($gruposUsuarios as $key => $grupo)
                            <optgroup label="{{ $key }}">
                                @foreach($grupo as $key2 => $user)
                                    <option value="{{ $key2 }}" @if (in_array($key2, $grupoDivulgacaoSelecionado)) selected="selected"@endif>{{ $user }}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>
            </div>   
        </div>       
    </div>
    
    <legend>Normas</legend>
    <hr>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <div class="col-md-10 control-label font-bold">
                    {!! Form::label('normaDoc', 'NORMAS') !!}
                </div>
                <div class="col-md-12">
                    <select multiple  id="optgroup-newNormaDoc" name="grupoNorma[]">
                        @foreach($normas as $key => $norma)
                            <optgroup label="{{ $norma->descricao }}">
                                @foreach($norma->docsItemNorma as $key2 => $itemNorma)
                                    <option value="{{ $itemNorma->id }}" @if (in_array($itemNorma->id, $normasSelecionados)) selected="selected"@endif >{{ $itemNorma->descricao }}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>
            </div>   
        </div>       
    </div>
</div>



@section('footer')
<script>
    $(document).ready(function() {
        /*
        * MultiSelect 
        */
        carregaOptGroup();
        
        buscaEtapas();
        buscaSetor();

        $('#optgroup-newNormaDoc').multiSelect({
            selectableOptgroup: true,
            selectableHeader: "<input type='text' class='form-control search-input' autocomplete='off' placeholder='Pesquisar item da norma'>",
            selectionHeader: "<input type='text' class='form-control search-input' autocomplete='off' placeholder='Pesquisar item da norma'>",
            afterInit: function(ms){
                var that = this,
                    $selectableSearch = that.$selectableUl.prev(),
                    $selectionSearch = that.$selectionUl.prev(),
                    selectableSearchString = '#'+that.$container.attr('id')+' .ms-elem-selectable:not(.ms-selected)',
                    selectionSearchString = '#'+that.$container.attr('id')+' .ms-elem-selection.ms-selected';

                that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
                .on('keydown', function(e){
                if (e.which === 40){
                    that.$selectableUl.focus();
                    return false;
                }
                });

                that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
                .on('keydown', function(e){
                if (e.which == 40){
                    that.$selectionUl.focus();
                    return false;
                }
                });
            },
            afterSelect: function(){
                this.qs1.cache();
                this.qs2.cache();
            },
            afterDeselect: function(values){
                this.qs1.cache();
                this.qs2.cache();
            }
        });


        $('#tipoDocumento').on('change', function(){
            buscaEtapas();
            buscaSetor();
        });


        $('#radio1, #radio2').on('change', function(){
            if($('#radio1').prop('checked') == true){
                document.createDocumento.action = "{{ route('docs.documento.importar-documento') }}";
            }else if($('#radio2').prop('checked') == true){
                document.createDocumento.action = "{{ route('docs.documento.criar-documento') }}";
            }
        });

        $('#copiaControlada').on('change', function(){
            if($(this).prop('checked') == true){
                $('#btnGerenciarCopiaControlada').show();
            }else{
                $('#btnGerenciarCopiaControlada').hide();
            }
        });
        
    });

    function buscaEtapas()
    {
        var id = $('#tipoDocumento').val();
        var documento = $('#idDocumento').val();
        let obj = {'id': id};
        if(id != ''){
            $('.aprovadores').empty();
            ajaxMethod('POST', "{{ URL::route('docs.tipo-documento.etapa-fluxo') }}", obj).then(response => {
                
                if(response.response == 'erro') {
                    swal2_alert_error_support("Tivemos um problema ao buscar as informações das etapas.");
                }else{
                    monta(response, documento);
                } 
            }, error => {
                console.log(error);
            });
        } 
    }

    function buscaSetor()
    {
        var documento = $('#idDocumento').val();
        var id = $('#tipoDocumento').val();
        let obj = {'id': id, 'idDocumento': documento};
        if(id != ''){
            $('#setor').empty();
            ajaxMethod('POST', "{{ URL::route('docs.tipo-documento-setor.setor') }}", obj).then(response => {
                
                if(response.response == 'erro') {
                    swal2_alert_error_support("Tivemos um problema ao buscar o setores vinculados ao tipo de documento.");
                }else{
                    montaSetor(response.data);
                } 
                
            }, error => {
                console.log(error);
            });
        } 
    }

    function carregaOptGroup()
    {
        $('.optgroup').multiSelect({
            selectableOptgroup: true,
            selectableHeader: "<input type='text' class='form-control search-input' autocomplete='off' placeholder='Pesquisar usuários'>",
            selectionHeader: "<input type='text' class='form-control search-input' autocomplete='off' placeholder='Pesquisar usuários'>",
            afterInit: function(ms){
                var that = this,
                    $selectableSearch = that.$selectableUl.prev(),
                    $selectionSearch = that.$selectionUl.prev(),
                    selectableSearchString = '#'+that.$container.attr('id')+' .ms-elem-selectable:not(.ms-selected)',
                    selectionSearchString = '#'+that.$container.attr('id')+' .ms-elem-selection.ms-selected';

                that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
                .on('keydown', function(e){
                if (e.which === 40){
                    that.$selectableUl.focus();
                    return false;
                }
                });

                that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
                .on('keydown', function(e){
                if (e.which == 40){
                    that.$selectionUl.focus();
                    return false;
                }
                });
            },
            afterSelect: function(){
                this.qs1.cache();
                this.qs2.cache();
            },
            afterDeselect: function(values){
                this.qs1.cache();
                this.qs2.cache();
            }
        });
    }

    function buscaAprovadores(etapa, documento)
    {
        return new Promise(function (resolve, reject) {
            let obj = {'etapa': etapa, 'documento': documento};
            ajaxMethod('POST', "{{ URL::route('docs.user-etapa-documento.aprovadores') }}", obj).then(response => {
                if(response.response == 'erro') {
                    reject();
                    swal2_alert_error_support("Tivemos um problema ao buscar as informações das etapas.");
                }else{
                    let retorno = response.data;
                    resolve(retorno);
                }
            });
        });

    }

    function buscaDocumentosPai(tipoDocumento, documento)
    {
        return new Promise(function (resolve, reject) {
            let obj = {'tipo': tipoDocumento, 'documento': documento};
            ajaxMethod('POST', "{{ URL::route('docs.documento.documento-pai-por-tipo') }}", obj).then(response => {
                if(response.response == 'erro') {
                    reject();
                    swal2_alert_error_support("Tivemos um problema ao buscar as informações das etapas.");
                }else{
                    resolve(response.data);
                }
            });
        });

    }

    async function monta(response, documento)
    {
        let linha = '';
        let retorno = response.data.etapas;
        let retornoTipoDocumento = response.data.tipo;
     
        obrigatoriedadeCampos(retornoTipoDocumento['vinculo_obrigatorio_outros_doc'], 'documentoVinculado', 'divDocumentosVinculados', 'labelDocumentosVinculados');
        obrigatoriedadeCampos(retornoTipoDocumento['vinculo_obrigatorio'], 'documentoPai', 'divDocumentoPai', 'labelDocumentoPai');
        
        $('#documentoPai').empty();
        await buscaDocumentosPai(retornoTipoDocumento['tipo_documento_pai'], documento).then(response => {
            let options = '';
            for (let index = 0; index < response.length; index++) {
                const element = response[index];
                let selecionado = element.select == true ? 'selected' : '';
                options += "<option value='"+element.id+"' "+selecionado+" >"+element.nome+"</option>"
            }
            $('#documentoPai').append(options).selectpicker('refresh');
        });

        for (let index = 0; index < retorno.length; index++) {
            const element = retorno[index];
            linha += "<div class='col-md-12'>";
            linha += "<div class='form-group'>";
            linha += "<div class='col-md-10 control-label font-bold'>";
            linha += "<label for="+element.nome+">Etapa: "+element.nome.toUpperCase()+"</label>";
            linha += "</div>";
            linha += "<div class='col-md-12'>";
            
            let aprovadores = [];    
            await buscaAprovadores(element.id, documento).then(response=>{
                if(response != undefined){
                    for (let index = 0; index < response.length; index++) {
                        aprovadores.push(response[index]);
                    }
                }
                
            });
            
            let obrigatorio = element.obrigatorio == true ? "required='required'" : "";
            linha += "<select multiple class='optgroup' "+obrigatorio+"  id='optgroup-newGrupo"+element.id+"' name='grupo"+element.id+"[]''>";
            
            @foreach($gruposUsuarios as $key => $grupo)
            linha += "<optgroup label='{{ $key }}'>";
                    @foreach($grupo as $key2 => $user)
                        exist = aprovadores.indexOf('{{$key2}}') > -1 ? "selected='selected'": "";
                        linha += "<option value='{{$key2}}' "+exist+">{{ $user }}</option>";
                    @endforeach
            linha += "</optgroup>";
            @endforeach
            linha += "</select>";   
            linha += "</div>";
            linha += "</div>";
            linha += "</div>";
            linha += "</div>";
        }
        $('.aprovadores').append(linha);

        carregaOptGroup();
    }

    function montaSetor(response)
    {
        let options = "<option value=''>Nada selecionado</option>";
        for (let index = 0; index < response.length; index++) {
            const element = response[index];
            let selecionado = element.select == true ? 'selected' : '';
            options += "<option value='"+element.id+"' "+selecionado+" >"+element.nome+"</option>"
        }
        $('#setor').append(options).selectpicker('refresh');
    }

    function obrigatoriedadeCampos(tipo, input, div, label)
    {
        if(tipo == true)
        {    
            $('#'+input).attr('required', true);
            $('#'+div).attr('class', 'form-group required');
            $('#'+label).attr('class', 'control-label');
        }else {
            $('#'+input).attr('required', false);
            $('#'+div).attr('class', 'form-group');
            $('#'+label).removeAttr('class');
        } 
    }
</script>
@endsection