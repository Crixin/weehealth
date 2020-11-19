@extends('layouts.app')

@extends('layouts.menuDocs')
@yield('menu')

@section('content')  
    <!-- O que fazer nesta situação? -->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>

	<div class="page-wrapper">
        <div class="container-fluid">

            <div class="row page-titles">
                <div class="col-md-5 col-8 align-self-center">
                    <h3 class="text-themecolor m-b-0 m-t-0">Configurações</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ URL::route('home') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ URL::route('configuracoes') }}">Configurações</a></li>
                        <li class="breadcrumb-item active">Vincular Usuários</li>
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

                            @if( isset($setorDosAprovadores) )
                                <h2>Definindo os aprovadores do setor <span class="text-primary"> {{ $text_agrupamento }} </span> </h2>
                                <div class="col-md-12 alert alert-warning">
                                    <h5 class="box-title">Clique sobre os usuários que deseja vincular. <small>Caso deseje vincular todos os usuários do setor, clique sobre o nome do setor.</small></h5>
                                    <h5 class="box-title">Para desvincular, <b>apenas clique sobre o usuário</b>. <small>Ao fazer isso, o usuário <b>já foi desvinculado </b>.</small></h5>
                                </div>
                            @else
                                <h4>Vinculando usuários <span class="text-info"> {{ $text_agrupamento }} </span> </h4>
                                <div class="col-md-12 alert alert-info">
                                    <h5 class="box-title">Clique sobre os usuários que deseja vincular. <small>Caso deseje vincular todos os usuários do setor, clique sobre o nome do setor.</small></h5>
                                    <h5 class="box-title">Para desvincular, selecione <b>um</b> usuário por vez. <small>Pedimos isso em razão da definição de um novo setor para cada usuário desvinculado.</small></h5>
                                </div>
                            @endif

                            
                            <div class="mt-4">

                                {!! Form::open(['route' => 'configuracoes.link.save', 'class' => 'form-horizontal', 'id' => 'form-generate-document']) !!}
                                    
                                
                                
                                    @if( isset($setor) )
                                        {!! Form::hidden('tipo_agrupamento', Constants::$ID_TIPO_AGRUPAMENTO_SETOR) !!}
                                        {!! Form::hidden('id_agrupamento', $setor->id) !!}
                                       
                                        <select multiple id="optgroup" class="searchable" name="usersLinked[]" data-setor="{{$setor->id}}">
                                            @foreach($setoresUsuarios as $key => $su)
                                                @if($key == $checkGrouping )
                                                    <optgroup label="{{$key}}">
                                                        @foreach($su as $key2 => $user)
                                                            <option selected value="{{$key2}}">{{$user}}</option>
                                                        @endforeach
                                                    </optgroup>
                                                @else
                                                    <optgroup label="{{$key}}">
                                                        @foreach($su as $key2 => $user)
                                                            <option value="{{$key2}}">{{$user}}</option>
                                                        @endforeach
                                                    </optgroup>
                                                @endif
                                            @endforeach
                                        </select>



                                    @elseif( isset($setorDosAprovadores) )
                                        {!! Form::hidden('tipo_agrupamento', Constants::$ID_TIPO_AGRUPAMENTO_APROVADORES_POR_SETOR) !!}
                                        {!! Form::hidden('id_agrupamento', $setorDosAprovadores->id) !!}
                                       
                                        <select multiple id="optgroup-aprovadores" name="usersLinked[]" data-setor="{{$setorDosAprovadores->id}}">
                                            @foreach($setoresUsuarios as $key => $su)
                                                <optgroup label="{{$key}}">
                                                    @foreach($su as $key2 => $user)
                                                        @if( is_array($checkGrouping) && in_array($key2, $checkGrouping) )
                                                            <option selected value="{{$key2}}">{{$user}}</option>
                                                        @else
                                                            <option value="{{$key2}}">{{$user}}</option>
                                                        @endif
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        </select>

                                        <div class="modal fade" id="modalChangeAprovador" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document" style="width:40%">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Novo aprovador</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <input type="hidden" id="aprovadorVelho" />
                                                        <select class="form-control custom-select" id="optgroup-new-aprovador" data-setor="{{$setorDosAprovadores->id}}">
                                                            @foreach($setoresUsuarios as $key => $su)
                                                                <optgroup label="{{$key}}">
                                                                    @foreach($su as $key2 => $user)
                                                                        <option value="{{$key2}}">{{$user}}</option>
                                                                    @endforeach
                                                                </optgroup>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                        <button type="button" class="btn btn-success" onclick="atualizaAprovador();" >Salvar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    @elseif( isset($grupoT) )
                                        {!! Form::hidden('tipo_agrupamento', Constants::$ID_TIPO_AGRUPAMENTO_GRUPO_TREINAMENTO) !!}
                                        {!! Form::hidden('id_agrupamento', $grupoT->id) !!}

                                        <select multiple id="optgroup-grupoT" name="usersLinked[]" data-setor="{{$grupoT->id}}">
                                            @foreach($setoresUsuarios as $key => $su)
                                                <optgroup label="{{$key}}">
                                                    @foreach($su as $key2 => $user)
                                                        @if( in_array($user, $usuariosJaVinculados[$checkGrouping]) )
                                                            <option selected value="{{$key2}}">{{$user}}</option>
                                                        @else
                                                            <option value="{{$key2}}">{{$user}}</option>
                                                        @endif
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        </select>



                                    @elseif( isset($grupoD) )
                                        {!! Form::hidden('tipo_agrupamento', Constants::$ID_TIPO_AGRUPAMENTO_GRUPO_DIVULGACAO) !!}
                                        {!! Form::hidden('id_agrupamento', $grupoD->id) !!}

                                        <select multiple id="optgroup-grupoD" name="usersLinked[]" data-setor="{{$grupoD->id}}">
                                            @foreach($setoresUsuarios as $key => $su)
                                                <optgroup label="{{$key}}">
                                                    @foreach($su as $key2 => $user)
                                                        @if( in_array($user, $usuariosJaVinculados[$checkGrouping]) )
                                                            <option selected value="{{$key2}}">{{$user}}</option>
                                                        @else
                                                            <option value="{{$key2}}">{{$user}}</option>
                                                        @endif
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        </select>   
                                    @endif

                                    <div class="pull-right">
                                        <br>
                                        <input type="submit" id="btn-save-new-document" class="btn btn-lg btn-success" value="Atualizar Vinculações">
                                    </div>
                                {!! Form::close() !!}
                            </div>



                        </div>
                    </div>
                </div>
            </div>
            <!-- End Page Content -->

            

        </div>
    </div>
@endsection


@section('footer')
<script src="{{ asset('plugins/multiselect/js/jquery.multi-select.js') }}"></script>
<script src="{{ asset('plugins/quicksearch/jquery.quicksearch.js') }}"></script>
<script>

    $('#modalChangeAprovador').on('hidden.bs.modal', function () {
        location.reload();
    })


    function atualizaAprovador() {
  
        let setor = $("#optgroup-new-aprovador").data('setor');
        let newAprovador = $("#optgroup-new-aprovador").val();
        let oldAprovador = $("#aprovadorVelho").val();
        
        let obj = {'id_setor': setor, 'new_aprovador': newAprovador, 'old_aprovador': oldAprovador, 'tipo_grupo': 'aprovadores'};

        ajaxMethod('POST', " {{ URL::route('ajax.aprovador.substituto') }} ", obj).then(result => {
            showToast('Sucesso!', 'Usuário desvinculado e novo aprovador vinculado com sucesso.', 'success');
            setTimeout(() => { location.reload() }, 1000);
        })
        .catch( error => {
            showToast('Erro!', 'Falha ao desvincular o usuário.', 'error');
            setTimeout(() => { location.reload() }, 1000);
        });
    }


        /*
        * 
        * MultiSelect de SETOR
        * 
        */
        $('#optgroup').multiSelect({
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
                
                window.sessionStorage.setItem("id_usuario_atual_desvinculacao", values[0]);
                var id = $("#optgroup").data('setor');
                var obj = {'id_setor': id};
                ajaxMethod('POST', " {{ URL::route('ajax.setores.retornaSetoresExcetoUm') }} ", obj).then(function(result) {
                    $("#novo_setor_do_usuario option").remove();
                    
                    Object.keys(result.response).forEach(function(key) {
                        $('#novo_setor_do_usuario').append($('<option>', { 
                            value: key,
                            text : result.response[key]
                        }));
                    });
                }, function(err) {
                    console.log(err);
                });

                $("#new-sector-to-user").modal({
                    backdrop: 'static',
                    'keyboard': false
                });
            }
        });


        /*
        * 
        * MultiSelect de APROVADORES [DIRETORIA / GERÊNCIA old]
        * 
        */
        $('#optgroup-aprovadores').multiSelect({ 
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
                var id = $("#optgroup-aprovadores").data('setor');
                var obj = {'id_setor': id, 'id_user': values[0], 'tipo_grupo': 'aprovadores'};

                ajaxMethod('POST', " {{ URL::route('ajax.usuarios.removerDoGrupo') }} ", obj).then(function(result) {
                    if( result.response == 'delete_success' ) {
                        showToast('Sucesso!', 'Usuário desvinculado com sucesso.', 'success');
                    } else {
                        swal({   
                            title: "Ação Bloqueada!",   
                            text: `Este usuário é o aprovador do(s) documento(s) ${result.codes.join(', ')} e por isso não pode ser desvinculado sem selecionar outro aprovador. `,   
                            type: "warning",   
                            showCancelButton: true,   
                            confirmButtonText: "Vincular novo aprovador",   
                            cancelButtonText: "Cancelar",   
                            closeOnConfirm: false,   
                            closeOnCancel: false 
                        }, function(isConfirm){   
                            if (isConfirm) {
                                $("#aprovadorVelho").val(values[0])     
                                $("#modalChangeAprovador").modal('show');
                                swal.close();
                            } else {     
                                location.reload();
                            }  
                        });
                    }
                }, function(err) {
                });
            }
        });

        /*
        * 
        * MultiSelect de GRUPOS DE TREINAMENTO
        *
        */
        $('#optgroup-grupoT').multiSelect({
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
                var id = $("#optgroup-grupoT").data('setor');
                var obj = {'id_grupo': id, 'id_user': values[0], 'tipo_grupo': 'treinamento'};

                ajaxMethod('POST', " {{ URL::route('ajax.usuarios.removerDoGrupo') }} ", obj).then(function(result) {
                }, function(err) {
                });
            }
        });


        /*
        * 
        * MultiSelect de GRUPOS DE DIVULGAÇÃO
        *
        */
        $('#optgroup-grupoD').multiSelect({
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
                var id = $("#optgroup-grupoD").data('setor');
                var obj = {'id_grupo': id, 'id_user': values[0], 'tipo_grupo': 'divulgacao'};

                ajaxMethod('POST', " {{ URL::route('ajax.usuarios.removerDoGrupo') }} ", obj).then(function(result) {
                }, function(err) {
                });
            }
        });


        /*
        * 
        * MultiSelect de NOVA ÁREA DE INTERESSE
        *
        */
        $('#optgroup-newAreaDeInteresse').multiSelect({
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
</script>

@endsection

