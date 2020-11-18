@extends('core::layouts.app')

@extends('core::layouts.menuDocs')
@yield('menu')

@section('content')
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    
    <link href="{{ asset('plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}"  rel="stylesheet">
    <script src="{{ asset('plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js') }}"></script>



	<div class="page-wrapper">
        <div class="container-fluid">
            

            <div class="row page-titles">
                <div class="col-md-5 col-8 align-self-center">
                    <h3 class="text-themecolor m-b-0 m-t-0">Documentação</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ URL::route('home') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ URL::route('documentacao') }}">Documentação</a></li>
                        <li class="breadcrumb-item active">Atualizar Informações</li>
                    </ol>
                </div>
                <div class="col-md-7 col-4 align-self-center">
                    <div class="">
                        <button class="right-side-toggle waves-light btn-success btn btn-circle btn-xl pull-right m-l-10   btn-badge badge-top-right" data-count="{{ count(\App\Classes\Helpers::instance()->getNotifications( Auth::user()->id )) }}">
                            <i class="ti-comment-alt text-white"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            
            <!-- Start Page Content -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title mb-4">Atualizando informações do documento: <span class="text-themecolor">{{ explode(Constants::$SUFIXO_REVISAO_NOS_TITULO_DOCUMENTOS, $documento->nome)[0] }}</span></h3>
                            
                            {!! Form::open(['route' => 'documentacao.update-info', 'class' => 'form-horizontal', 'id' => 'form-update-document']) !!}
                                {!! Form::hidden('doc_id', $documento->id) !!}

                                <!-- Linha 1 -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="col-md-6 control-label font-bold">
                                                {!! Form::label('aprovadores', 'APROVADORES:') !!}
                                            </div>
                                            <div class="col-md-12">

                                                {!! Form::select('aprovador', $aprovadores, $documento->aprovador_id, ['class' => 'form-control  custom-select', 'id' => 'aprovadores']) !!}
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="col-md-12 control-label font-bold">
                                                {!! Form::label('copiaControlada', 'CÓPIA CONTROLADA:') !!}
                                            </div>
                                            <div class="col-md-12">
                                                <input name="copiaControlada" type="radio" id="sim" value="true" class="with-gap radio-col-blue" {{ ($documento->copia_controlada) ? 'checked' : '' }} />
                                                <label for="sim">Sim</label>
                                                <input name="copiaControlada" type="radio" id="nao" value="false" class="with-gap radio-col-light-blue" {{ ($documento->copia_controlada) ? '' : 'checked' }}/>
                                                <label for="nao">Não</label>
                                            </div>
                                        </div>
                                    </div>  
                                    @if( Auth::user()->setor_id == Constants::$ID_SETOR_QUALIDADE ) 
                                        <div class="col-md-3">
                                            <button type="button" id="btnGerenciadorCopiasControladas" class="btn waves-effect btn-lg waves-light btn-info" style="display: none">Gerenciar </button>
                                        </div>
                                    @endif
                                </div>

                                <!-- Linha 3 -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="col-md-10 control-label font-bold">
                                                {!! Form::label('areaInteresse', 'ÁREA DE INTERESSE:') !!}
                                            </div>
                                            <div class="col-md-12">
                                                <select multiple id="optgroup-newAreaDeInteresse-update" name="areaInteresse[]">
                                                    @foreach($setoresUsuarios as $key => $su)
                                                        <optgroup label="{{ $key }}">
                                                            @foreach($su as $key2 => $user)
                                                                @if( in_array($key2, $usuariosAreaInteresseDocumento) )
                                                                    <option selected value="{{ $key2 }}">{{ $user }}</option>
                                                                @else
                                                                    <option value="{{ $key2 }}">{{ $user }}</option>
                                                                @endif
                                                            @endforeach
                                                        </optgroup>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>   
                                    </div>                                                    
                                </div>


                                @if( $documento->nivel_acesso == Constants::$NIVEL_ACESSO_DOC_RESTRITO || $documento->nivel_acesso == Constants::$NIVEL_ACESSO_DOC_CONFIDENCIAL )
                                    <!-- Linha 4 -->
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div class="col-md-10 control-label font-bold">
                                                    {!! Form::label('extraUsers', 'USUÁRIOS EXTRAS:') !!}
                                                </div>
                                                <div class="col-md-12">
                                                    <select multiple id="optgroup-newExtraUsers" name="extraUsers[]">
                                                        @foreach($setoresUsuarios as $key => $su)
                                                            <optgroup label="{{ $key }}">
                                                                @foreach($su as $key2 => $user)
                                                                    @if( in_array($key2, $usuariosExtraDocumento) )
                                                                        <option selected value="{{ $key2 }}">{{ $user }}</option>
                                                                    @else
                                                                        <option value="{{ $key2 }}">{{ $user }}</option>
                                                                    @endif
                                                                @endforeach
                                                            </optgroup>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>   
                                        </div>                                                    
                                    </div>
                                @endif


                                <!-- Linha 5 --> 
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="col-md-10 control-label font-bold">
                                                {!! Form::label('grupoTreinamentoDoc', 'GRUPO DE TREINAMENTO:') !!}
                                            </div>
                                            <div class="col-md-12">
                                                <select multiple id="optgroup-newGrupoTreinamentoDoc-update" name="grupoTreinamentoDoc[]">
                                                    @foreach($setoresUsuarios as $key => $su)
                                                        <optgroup label="{{ $key }}">
                                                            @foreach($su as $key2 => $user)
                                                                @if( in_array($key2, $usuariosGrupoTreinamentoDocumento) )
                                                                    <option selected value="{{ $key2 }}">{{ $user }}</option>
                                                                @else
                                                                    <option value="{{ $key2 }}">{{ $user }}</option>
                                                                @endif
                                                            @endforeach
                                                        </optgroup>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>   
                                    </div>                                                    
                                </div>

                                <!-- Linha 6 --> 
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="col-md-10 control-label font-bold">
                                                {!! Form::label('grupoDivulgacaoDoc', 'GRUPO DE DIVULGAÇÃO:') !!}
                                            </div>
                                            <div class="col-md-12">
                                                <select multiple id="optgroup-newGrupoDivulgacaoDoc-update" name="grupoDivulgacaoDoc[]">
                                                    @foreach($setoresUsuarios as $key => $su)
                                                        <optgroup label="{{ $key }}">
                                                            @foreach($su as $key2 => $user)
                                                                @if( in_array($key2, $usuariosGrupoDivulgacaoDocumento) )
                                                                    <option selected value="{{ $key2 }}">{{ $user }}</option>
                                                                @else
                                                                    <option value="{{ $key2 }}">{{ $user }}</option>
                                                                @endif
                                                            @endforeach
                                                        </optgroup>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>   
                                    </div>                                                    
                                </div>                               
                                
                                <!-- Linha 7 --> 
                                <div class="row">
                                    <div class="col-md-6">
                                        @if( Auth::user()->permissao_elaborador || Auth::user()->setor_id == Constants::$ID_SETOR_QUALIDADE )
                                            <div class="form-group">
                                                <div class="col-md-10 control-label font-bold">
                                                    {!! Form::label('validadeDocumento', 'VALIDADE DO DOCUMENTO:') !!}
                                                </div>
                                                <div class="col-md-12">
                                                    {!! Form::text('validadeDocumento', \Carbon\Carbon::createFromFormat('Y-m-d', $documento->validade)->format('d/n/Y'), ['class' => 'form-control', 'id' => 'mdate']) !!}
                                                </div>
                                            </div>
                                        @else 
                                            <div class="form-group">
                                                <div class="col-md-12">
                                                    <h6>A validade só pode ser alterada por um usuário do setor Processos ou que possua a <i>permissão de elaborador</i>. </h6>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="col-md-10 control-label font-bold">
                                                {!! Form::label('nivelAcessoDoc', 'NÍVEL DE ACESSO:') !!}
                                            </div>
                                            <div class="col-md-12">
                                                {!! Form::select('nivelAcessoDoc', [Constants::$NIVEL_ACESSO_DOC_LIVRE, Constants::$NIVEL_ACESSO_DOC_RESTRITO, Constants::$NIVEL_ACESSO_DOC_CONFIDENCIAL], $documento->nivel_acesso_fake_id, ['class' => 'form-control  custom-select']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>    

                                <!-- Linha 8 --> 
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="col-md-6 control-label font-bold">
                                                {!! Form::label('tituloDocumento', 'TÍTULO DO DOCUMENTO:') !!}
                                            </div>
                                            <div class="col-md-12">
                                                {!! Form::text('tituloDocumento', explode(Constants::$SUFIXO_REVISAO_NOS_TITULO_DOCUMENTOS, $documento->nome)[0], ['class' => 'form-control']) !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                    <!-- Linha 7 --> 
                                <div class="row">
                                    <div class="col-md-6"></div>
                                    <div class="col-md-6">
                                        <div class="col-md-12">
                                            <button type="submit" class="btn waves-effect waves-light btn-block btn-lg btn-secondary">ATUALIZAR INFORMAÇÕES</button>
                                        </div>
                                    </div>
                                </div>
                            {!! Form::close() !!}

                        </div>
                    </div>
                </div>
            </div>
            <!-- End Page Content -->

            
            <!-- Modal de Cópias Controladas -->
            <div class="modal fade" id="modalCopiasControladas" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title" id="exampleModalLabel">Gerenciando cópias controladas do documento: <span class="text-themecolor">{{ explode(Constants::$SUFIXO_REVISAO_NOS_TITULO_DOCUMENTOS, $documento->nome)[0] }}</span></h3>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>                        
                        <div class="modal-body">
                            <div class="row">
                                
                                <div class="col-md-12">
                                    <h4>Listagem de cópias controladas do documento</h4>
                                    <div class="table-responsive">
                                        <table class="table table-condensed">
                                            <thead>
                                                <tr>
                                                    <th class="text-nowrap text-center">Nº de Cópias</th>
                                                    <th class="text-nowrap text-center">Revisão</th>
                                                    <th class="text-nowrap text-center">Setor</th>
                                                    <th class="text-nowrap text-center">Responsável</th>
                                                    <th class="text-nowrap text-center">Ações</th>
                                                </tr>
                                            </thead>
                                            <tbody id="copiasControladas-corpo-tabela">
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                    <hr style="border-top: 2px solid darkgray">
                                </div>
                                
                                <div class="col-md-12">
                                    <h4>Novo registro de cópia controlada</h4>
                                    {!! Form::open(['route' => 'ajax.copiaControlada.save', 'method' => 'POST', 'id' => 'formCopiaControlada']) !!}
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <div class="col-md-10 control-label font-bold">
                                                        {!! Form::label('numeroDeCopias', 'NÚMERO') !!}
                                                    </div>
                                                    <div class="col-md-12">
                                                        {!! Form::number('numeroDeCopias', null, ['class' => 'form-control', 'max' => '999', 'id' => 'numeroDeCopias']) !!}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <div class="col-md-10 control-label font-bold">
                                                        {!! Form::label('revisaoDasCopias', 'REVISÃO') !!}
                                                    </div>
                                                    <div class="col-md-12">
                                                        {!! Form::text('revisaoDasCopias', null, ['class' => 'form-control', 'maxlength' => '10', 'id' => 'revisaoDasCopias']) !!}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-7">
                                                <div class="form-group">
                                                    <div class="col-md-10 control-label font-bold">
                                                        {!! Form::label('setorDasCopias', 'SETOR') !!}
                                                    </div>
                                                    <div class="col-md-12">
                                                        {!! Form::text('setorDasCopias', null, ['class' => 'form-control', 'maxlength' => '35', 'id' => 'setorDasCopias']) !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-9">
                                                <div class="form-group required">
                                                    <div class="col-md-10 control-label font-bold">
                                                        {!! Form::label('responsavel', 'RESPONSÁVEL PELA SUBSTITUIÇÃO') !!}
                                                    </div>
                                                    <div class="col-md-12">
                                                        <select id="responsavel" name="responsavel" class="form-control select2 m-b-10" style="width: 100%" data-placeholder="Digite..." required>
                                                            @foreach ($usuarios as $id => $nome)
                                                                <option value="{{ $id }}">{{ $nome }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <button type="submit" class="btn btn-block btn-info waves-effect pull-right" style="margin-top: 20%">Salvar Registro</button>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div id="mensagem-copia-controlada"></div>
                                            </div>
                                        </div>
                                    {!! Form::close() !!}
                                </div>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-lg btn-secondary waves-effect" data-dismiss="modal">Fechar</button>
                        </div>
                    </div>
                </div>
            </div>



        </div>
    </div>
@endsection


@section('footer')

    <script>
        $(".select2").select2();
    </script>

    <script src="{{ asset('plugins/multiselect/js/jquery.multi-select.js') }}"></script>
    <script src="{{ asset('plugins/quicksearch/jquery.quicksearch.js') }}"></script>
    <script>        
        $(function(){
            // Material Date picker   
            $('#mdate').bootstrapMaterialDatePicker({ weekStart : 0, time: false, minDate: new Date(), lang: 'pt-br', format: 'DD/M/YYYY', currentDate: new Date(), cancelText: 'Cancelar', okText: 'Definir' });


            /*
            * 
            * Todas as ações envolvendo 'Cópia Controlada': radio button, modal, etc...
            *
            */

            checkValueCopiaControlada();

            (function(){
                var obj = {'idDoc': "{{ $documento->id }}"};
                ajaxMethod('POST', " {{ URL::route('ajax.copiaControlada.getCopias') }} ", obj).then(function(result) {
                    if(result.response) {
                        var registros = result.response;
                        refreshTable(registros);
                    } else {
                        console.log(result);
                        $("#mensagem-copia-controlada").append("<div class='alert alert-danger'>Ops! Tivemos um problema ao carregar as cópias controladas desse documento.</div>");
                    }
                });   
            })();

            // Função que vai verificar se o valor do documento possui 'cópia controlada' está marcado como 'sim' ou 'não'
            function checkValueCopiaControlada() {
                var vlr = $('input[name=copiaControlada]:checked', '#form-update-document')[0].id;
                var btn = $("#btnGerenciadorCopiasControladas");

                if (vlr == 'sim') {
                    btn.show();
                    $("#btnGerenciadorCopiasControladas").removeAttr("disabled");
                    $("#btnGerenciadorCopiasControladas").attr("data-toggle", "modal");
                    $("#btnGerenciadorCopiasControladas").attr("data-target", "#modalCopiasControladas");
                } else {
                    btn.hide();
                    $("#btnGerenciadorCopiasControladas").attr("disabled", "disabled");
                    $("#btnGerenciadorCopiasControladas").removeAttr("data-toggle");
                    $("#btnGerenciadorCopiasControladas").removeAttr("data-target");
                }

            }

            // Sempre que houver alteração no RadioButton de 'Cópia Controlada'
            $('input[type=radio][name=copiaControlada]').change(function() {
                checkValueCopiaControlada();
            });


            // Quando um novo registro de cópia controlada é salvo
            $("#formCopiaControlada").on('submit', function(evt) {
                evt.preventDefault();

                var idDoc = "{{ $documento->id }}";
                var form = $(this);
                $(form).append('<input type="hidden" name="idDoc" value="'+idDoc+'" />');

                var formData = new FormData( $(this)[0] );
                var url = form.attr('action');

                $.ajax({  
                    type: "POST",  
                    url: url,  
                    data: formData,
                    async: false,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        if(data.response) {
                            var registros = data.response;
                            refreshTable(registros);
                        } else {
                            console.log(data.error);
                            $("#mensagem-copia-controlada").append("<div class='alert alert-danger'>Ops! Tivemos um problema ao salvar o registro.</div>");
                        }
                    }
                });                 
            });


            // Quando o botão 'Remover' de uma cópia controlada é clicado
            $(document).on('click','.btn-remove-cc',function(evt){
                var obj = {'idCC': $(this).data('id'), 'idDoc': "{{ $documento->id }}"};
                ajaxMethod('POST', " {{ URL::route('ajax.copiaControlada.remove') }} ", obj).then(function(result) {
                    if(result.response) {
                        showToast('Sucesso!', 'Cópia controlada removida.', 'success');
                        refreshTable(result.response);
                    } else {
                        console.log(result.error);
                        $("#mensagem-copia-controlada").append("<div class='alert alert-danger'>Ops! Tivemos um problema ao remover o registro.</div>");
                    }
                });            
            });


            // Função para atualizar o corpo da tabela de registros de cópias controladas do documento (modal)
            function refreshTable(data) {
                $("#copiasControladas-corpo-tabela").empty();

                data.forEach(function(key) {
                    key.setor = (key.setor)?  key.setor : '-';
                    key.revisao = (key.revisao)?  key.revisao : '-';
                    key.setor = (key.setor)?  key.setor : '-';

                    var tr = '<tr>';
                    tr += '<td class="text-nowrap text-center">'+ key.numero_copias  +'</td><td class="text-nowrap text-center">'+ key.revisao +'</td><td class="text-nowrap text-center">'+ key.setor +'</td><td class="text-nowrap text-center">'+ key.responsavel +'</td><td class="text-nowrap text-center"><button class="btn btn-danger btn-remove-cc" data-id="'+key.id+'">Remover</button></td>'; 
                    tr += '</tr>';
                    $("#copiasControladas-corpo-tabela").append(tr);
                });

                cleanForm();
            }


            // Função que vai deixar os campos de cadastro de registro de uma nova cópia controlada em branco
            function cleanForm() {
                $("#numeroDeCopias").val(1);
                $("#revisaoDasCopias").val('');
                $("#setorDasCopias").val('');
                $('.select2').val(null).trigger('change');
                $("#mensagem-copia-controlada").empty();
            }


            /*
            * MultiSelect de NOVA ÁREA DE INTERESSE - UPDATE
            */
            $('#optgroup-newAreaDeInteresse-update').multiSelect({
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


            /*
            * MultiSelect de USUÁRIOS EXTRAS (Para poder permitir outras pessoas de ver o documento)
            */
            $('#optgroup-newExtraUsers').multiSelect({
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


            /*
            * MultiSelect de atualização do GRUPO DE TREINAMENTO DO DOCUMENTO
            */
            $('#optgroup-newGrupoTreinamentoDoc-update').multiSelect({
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


            /*
            * MultiSelect de atualização do GRUPO DE DIVULGAÇÃO DO DOCUMENTO
            */
            $('#optgroup-newGrupoDivulgacaoDoc-update').multiSelect({
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

        });
    </script>

@endsection
