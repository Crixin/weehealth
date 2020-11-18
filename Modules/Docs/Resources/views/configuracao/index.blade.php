@extends('core::layouts.app')

@extends('core::layouts.menuDocs')
@yield('menu')

@section('content')
    <!-- O que fazer nesta situação? -->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>

    @if (session('padrao_sucesso'))
		<input type="hidden" name="status" id="status" value="padrao_sucesso">
    @elseif (session('padrao_sucesso_dg'))
		<input type="hidden" name="status" id="status" value="padrao_sucesso_dg">
    @elseif (session('padrao_sucesso_pg'))
		<input type="hidden" name="status" id="status" value="padrao_sucesso_pg">
    @elseif (session('padrao_sucesso_mseg'))
		<input type="hidden" name="status" id="status" value="padrao_sucesso_mseg">
    @elseif (session('new_grouping_sucesso'))
		<input type="hidden" name="status" id="status" value="new_grouping_sucesso">
    @elseif (session('edit_sector_success'))
		<input type="hidden" name="status" id="status" value="edit_sector_success">
    @elseif (session('edit_training-group_success'))
		<input type="hidden" name="status" id="status" value="edit_training-group_success">
    @elseif (session('edit_disclosure-group_success'))
		<input type="hidden" name="status" id="status" value="edit_disclosure-group_success">
    @elseif (session('link_success'))
		<input type="hidden" name="status" id="status" value="link_success">
    @elseif (session('admin_qualidade_sucesso'))
		<input type="hidden" name="status" id="status" value="admin_qualidade_sucesso">
    @endif

    <script>
        $(document).ready(function(){
            // Verifica se acabou de gravar uma nova solicitação
            var status = $("#status").val();
            if(status == "padrao_sucesso") {
                showToast('Sucesso!', 'O número padrão para geração do código do INSTRUÇÕES DE TRABALHO foi atualizado.', 'success');
            } else if(status == "padrao_sucesso_dg") {
                showToast('Sucesso!', 'O número padrão para geração do código das DIRETRIZES DE GESTÃO foi atualizado.', 'success');
            } else if(status == "padrao_sucesso_pg") {
                showToast('Sucesso!', 'O número padrão para geração do código dos PROCEDIMENTOS DE GESTÃO foi atualizado.', 'success');
            } else if(status == "padrao_sucesso_pg") {
                showToast('Sucesso!', 'O número padrão para geração do código dos MANUAL SEG foi atualizado.', 'success');
            } else if(status == "new_grouping_sucesso") {
                showToast('Sucesso!', 'Novo agrupamento criado com sucesso.', 'success');
            } else if(status == "edit_sector_success") {
                showToast('Sucesso!', 'Setor editado com sucesso.', 'success');
            } else if(status == "edit_training-group_success") {
                showToast('Sucesso!', 'Grupo de Treinamento editado com sucesso.', 'success');
            } else if(status == "edit_disclosure-group_success") {
                showToast('Sucesso!', 'Grupo de Divulgação editado com sucesso.', 'success');
            } else if(status == "link_success") {
                showToast('Sucesso!', 'Vinculações atualizadas com sucesso.', 'success');
            } else if(status == "admin_qualidade_sucesso") {
                showToast('Sucesso!', 'Usuário com privilégios do setor Processos atualizado com sucesso.', 'success');
            }
        });
    </script>

    <!-- Page wrapper  -->
    <!-- ============================================================== -->
    <div class="page-wrapper">
        <!-- ============================================================== -->
        <!-- Container fluid  -->
        <!-- ============================================================== -->
        <div class="container-fluid">
            

            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <div class="row page-titles">
                <div class="col-md-5 col-8 align-self-center">
                    <h3 class="text-themecolor m-b-0 m-t-0">Configurações</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ URL::route('home') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Configurações</li>
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
            <!-- ============================================================== -->
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            
            
            <!-- ============================================================== -->
            <!-- Start Page Content -->
            <!-- ============================================================== -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            
                             <!-- Nav tabs -->
                             <ul class="nav nav-tabs nav-fill customtab" role="tablist">
                                <li class="nav-item"> <a class="nav-link font-bold active" data-toggle="tab" href="#usuarios" role="tab"><h3 class="hidden-xs-down">USUÁRIOS</h3></a> </li>  
                                <li class="nav-item"> <a class="nav-link font-bold" data-toggle="tab" href="#agrupamentos" role="tab"><h3 class="hidden-xs-down">AGRUPAMENTOS</h3></a> </li>
                                <li class="nav-item"> <a class="nav-link font-bold" data-toggle="tab" href="#novoAgrupamento" role="tab"><h3 class="hidden-xs-down">NOVO AGRUPAMENTO</h3></a> </li>
                                <li class="nav-item"> <a class="nav-link font-bold" data-toggle="tab" href="#padroes" role="tab"><h3 class="hidden-xs-down">PADRÕES</h3></a> </li>
                            </ul>
                            <!-- Tab panes -->
                            <div class="tab-content">

                                <!-- 
                                    /* TAB - Usuários */ 
                                -->
                                <div class="tab-pane active" id="usuarios" role="tabpanel">
                                    <div class="p-20">

                                        {!! Form::open(['route' => 'configuracoes.filter-sector', 'method' => 'POST', 'class' => 'form-horizontal']) !!}
                                            <div class="row margin-top-1percent mb-5">
                                                <div class="col-md-6">
                                                    {!! Form::select('search_setor', array(null => '- Setor -') + $setores, null, ['class' => 'form-control  custom-select', 'style' => 'width: 96%']) !!}
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <a href="{{ route('configuracoes') }}" class="btn btn-block waves-effect waves-light btn-secondary"><i class="fa fa-ban"></i> Limpar</a>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <button type="submit" class="btn btn-block waves-effect waves-light btn-outline-success"><i class="fa fa-search"></i> Buscar</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        {!! Form::close() !!} 
                                        
                                        <div class="row mt-2 margin-top-1percent">
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center">Nome</th>
                                                            <th class="text-center">E-mail</th>
                                                            <!-- <th class="text-center">Login de Usuário</th> -->
                                                            <th class="text-center text-nowrap">Permissão de Elaborador</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($usuariosCadastrados as $usuario)
                                                            <tr>
                                                                <td class="text-center"><a href="javascript:void(0)">{{ $usuario->name }}</a></td> 
                                                                <td class="text-center">{{ $usuario->email }}</td>
                                                                <!-- <td class="text-center">{{ $usuario->username }}</td> -->
                                                                <td class="text-center text-nowrap">
                                                                    <div class="switch">
                                                                        <label>DESABILITADA
                                                                            <input type="checkbox" class="switch-elaborador" data-user-id="{{ $usuario->id }}" {{ ($usuario->permissao_elaborador) ? 'checked' : '' }} ><span class="lever switch-col-light-blue"></span>HABILITADA
                                                                        </label>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <!-- 
                                    /* TAB - Agrupamentos */ 
                                -->
                                <div class="tab-pane" id="agrupamentos" role="tabpanel">
                                    <div class="p-20">
                                        
                                        <!-- Nav tabs -->
                                        <ul class="nav nav-tabs nav-fill customtab" role="tablist">
                                            <li class="nav-item"> <a class="nav-link speed-subtabs" style="cursor: pointer" data-toggle="collapse" data-target="#setores-empresa" aria-expanded="false" aria-controls="setores-empresa" role="tab"> <i class="fa fa-sitemap"></i> <span class="hidden-xs-down"> SETORES DA EMPRESA</span></a> </li>
                                            <li class="nav-item"> <a class="nav-link speed-subtabs" style="cursor: pointer" data-toggle="collapse" data-target="#aprovadores" aria-expanded="false" aria-controls="aprovadores" role="tab">  <i class="fa fa-group"></i>      <span class="hidden-xs-down"> APROVADORES</span></a> </li>
                                        </ul>
                                        <!-- Tab panes -->
                                        <div class="tab-content">

                                            <!-- Setores da Empresa -->
                                            <div class="collapse" id="setores-empresa" role="tabpanel">
                                                <div class="p-20">

                                                    <div class="row mt-2 margin-top-1percent">
                                                        <div class="table-responsive">
                                                            <table class="table">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Nome do Setor</th>
                                                                        <th>Sigla</th>
                                                                        <th>Descrição</th>
                                                                        <th>Ações</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach($setoresEmpresa as $setor)
                                                                        <tr>
                                                                            <td><a href="javascript:void(0)">{{ $setor->nome }}</a></td> 
                                                                            <td>{{ $setor->sigla }}</td>
                                                                            <td>{{ $setor->descricao }}</td>
                                                                            <td class="text-nowrap">
                                                                                <center>
                                                                                    @if( in_array($setor->id, [Constants::$ID_SETOR_CAPITAL_HUMANO, Constants::$ID_SETOR_QUALIDADE]))
                                                                                        <a href="{{ route('configuracoes.lista_presenca.aprovadores', ['id' => $setor->id]) }}" class="sa-warning mr-3" data-toggle="tooltip" data-original-title="Definir Aprovadores de Lista"> <i class="fa fa-check-square-o text-danger"></i> </a>
                                                                                    @endif

                                                                                    <a href="{{ route('configuracoes.link.users_sectors', ['id' => $setor->id]) }}" class="sa-warning mr-3" data-toggle="tooltip" data-original-title="Vincular Usuários"> <i class="fa fa-exchange text-info"></i> </a>
                                                                                    <a href="#" class="open-edit-sector-modal" data-id="{{$setor->id}}" data-nome="{{$setor->nome}}" data-sigla="{{$setor->sigla}}" data-desc="{{$setor->descricao}}" data-toggle="tooltip" data-original-title="Editar"> <i data-toggle="modal" data-target="#edit-sector-modal" class="fa fa-pencil text-inverse m-r-10"></i> </a>
                                                                                </center>
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                           
                                            <!-- Aprovadores -->
                                            <div class="collapse" id="aprovadores" role="tabpanel">
                                                <div class="p-20">
                                                
                                                    <div class="row">
                                                        <h5 class="alert container alert-info alert-dismissible" role="alert">
                                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                            Aqui serão definidos os <b>aprovadores</b> de cada setor da empresa, sendo que um usuário pode ser aprovador em mais do que um setor.
                                                        </h5>
                                                    </div>

                                                    <div class="row mt-2 margin-top-1percent">
                                                        <div class="table-responsive">
                                                            <table class="table">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Nome do Setor</th>
                                                                        <th>Sigla</th>
                                                                        <th>Descrição</th>
                                                                        <th>Ações</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach($setoresEmpresa as $setor)
                                                                        @if( $setor->nome != Constants::$NOME_SETOR_SEM_SETOR )
                                                                            <tr>
                                                                                <td><a href="javascript:void(0)">{{ $setor->nome }}</a></td> 
                                                                                <td>{{ $setor->sigla }}</td>
                                                                                <td>{{ $setor->descricao }}</td>
                                                                                <td class="text-nowrap">
                                                                                    <center>
                                                                                        <a href="{{ route('configuracoes.link.approver_sector', ['id' => $setor->id]) }}" class="sa-warning" data-toggle="tooltip" data-original-title="Definir Aprovadores"> <i class="fa fa-chain text-info"></i> </a>
                                                                                    </center>
                                                                                </td>
                                                                            </tr>
                                                                        @endif
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                </div>


                                <!-- 
                                    /* TAB - Novo Agrupamento */ 
                                -->
                                <div class="tab-pane  p-20" id="novoAgrupamento" role="tabpanel">
                                    <div class="col-md-12">
                                        <div class="col-md-12 mb-4">
                                            @if ($errors->any())
                                                <div class="alert alert-danger">
                                                    <ul>
                                                        @foreach ($errors->all() as $error)
                                                            <li>{{ $error }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                        </div>
                                          
                                        {!! Form::open(['route' => 'configuracoes.save.new-grouping', 'class' => 'form-horizontal', 'id' => 'form-generate-document']) !!}
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <div class="col-md-12 control-label font-bold">
                                                            {!! Form::label('nome_do_agrupamento', 'NOME DO AGRUPAMENTO:') !!}
                                                        </div>
                                                        <div class="col-md-12">
                                                            {!! Form::text('nome_do_agrupamento', null, ['class' => 'form-control']) !!}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <div class="col-md-6 control-label font-bold">
                                                            {!! Form::label('tipo_do_agrupamento', 'TIPO DO AGRUPAMENTO:') !!}
                                                        </div>
                                                        <div class="col-md-12">
                                                            {!! Form::select('tipo_do_agrupamento', ['Setor'], '', ['class' => 'form-control  custom-select']) !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <div class="col-md-6 control-label font-bold">
                                                            {!! Form::label('descrição', 'DESCRIÇÃO DO AGRUPAMENTO:') !!}
                                                        </div>
                                                        <div class="col-md-12">
                                                            {!! Form::textarea('descrição', null, ['class' => 'form-control']) !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 col-lg-12">
                                                    <div class="col-md-offset-6 col-md-3 pull-right">
                                                        {!! Form::submit('Salvar Agrupamento', ['class' => 'btn btn-lg btn-success']) !!}
                                                    </div>
                                                </div>
                                            </div>
                                        {!! Form::close() !!}

                                    </div> 
                                </div>


                                <!-- 
                                    /* TAB - Padrões */ 
                                -->
                                <div class="tab-pane  p-20" id="padroes" role="tabpanel">
                                    <div class="col-md-12"> 
                                           
                                            <div class="row">

                                                <div class="col-md-6" style="border-right: 1px solid lightgray;">
                                                    <div class="col-md-12 mb-4">
                                                        @if ($errors->any())
                                                            <div class="alert alert-danger">
                                                                <ul>
                                                                    @foreach ($errors->all() as $error)
                                                                        <li>{{ $error }}</li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        @else
                                                            <div class="alert alert-info">
                                                                <span><b>Exemplos de valores aceitos:</b></span>
                                                                <ul>
                                                                    <li>0       <span class="text-muted">- Código gerado será [1, 2, 3....]</span></li>
                                                                    <li>00       <span class="text-muted">- Código gerado será [1, 2, 3....]</span></li>
                                                                    <li>000     <span class="text-muted">- Código gerado será [001, 002, 003....]</span></li>
                                                                    <li>0000    <span class="text-muted">- Código gerado será [001.01, 002.01, 003.01....]</span></li>
                                                                </ul>
                                                                <small><b>Lembre-se:</b> são aceitos apenas 4 dígitos!</small>
                                                            </div>
                                                        @endif
                                                    </div>

                                                    
                                                    <div class="col-md-12 mb-5">
                                                        {!! Form::open(['route' => 'configuracoes.save.number-default', 'class' => 'form-horizontal']) !!}
                                                        <div class="form-group">
                                                            <div class="col-md-12 control-label font-bold">
                                                                {!! Form::label('numeroPadrao', 'PADRÃO PARA CÓDIGO - INSTRUÇÕES DE TRABALHO:') !!}
                                                            </div>
                                                            <div class="col-md-12">
                                                                {!! Form::text('numeroPadrao', $numeroPadraoParaCodigo, ['class' => 'form-control input-validation']) !!}
                                                            </div>
                                                            <div class="col-md-offset-8 pull-right col-md-4">
                                                                <button type="submit" class="btn btn-block btn-outline-success mt-2">Salvar</button>
                                                            </div>
                                                        </div>
                                                        {!! Form::close() !!}
                                                    </div>

                                                    <div class="col-md-12 mt-5 mb-4">
                                                        {!! Form::open(['route' => 'configuracoes.save.number-default-dg', 'class' => 'form-horizontal']) !!}
                                                        <div class="form-group">
                                                            <div class="col-md-12 control-label font-bold">
                                                                {!! Form::label('numeroPadraoDG', 'PADRÃO PARA CÓDIGO - DIRETRIZES DE GESTÃO:') !!}
                                                            </div>
                                                            <div class="col-md-12">
                                                                {!! Form::text('numeroPadraoDG', $numeroPadraoDG, ['class' => 'form-control input-validation']) !!}
                                                            </div>
                                                            <div class="col-md-offset-8 pull-right col-md-4">
                                                                <button type="submit" class="btn btn-block btn-outline-success mt-2">Salvar</button>
                                                            </div>
                                                        </div>
                                                        {!! Form::close() !!}
                                                    </div>

                                                    <div class="col-md-12 mt-5 mb-4">
                                                        {!! Form::open(['route' => 'configuracoes.save.number-default-pg', 'class' => 'form-horizontal']) !!}
                                                        <div class="form-group">
                                                            <div class="col-md-12 control-label font-bold">
                                                                {!! Form::label('numeroPadraoPG', 'PADRÃO PARA CÓDIGO - PROCEDIMENTOS DE GESTÃO:') !!}
                                                            </div>
                                                            <div class="col-md-12">
                                                                {!! Form::text('numeroPadraoPG', $numeroPadraoPG, ['class' => 'form-control input-validation']) !!}
                                                            </div>
                                                            <div class="col-md-offset-8 pull-right col-md-4">
                                                                <button type="submit" class="btn btn-block btn-outline-success mt-2">Salvar</button>
                                                            </div>
                                                        </div>
                                                        {!! Form::close() !!}
                                                    </div>
                                                    
                                                    <div class="col-md-12 mt-5 mb-4">
                                                        {!! Form::open(['route' => 'configuracoes.save.number-default-mseg', 'class' => 'form-horizontal']) !!}
                                                        <div class="form-group">
                                                            <div class="col-md-12 control-label font-bold">
                                                                {!! Form::label('numeroPadraoMSEG', 'PADRÃO PARA CÓDIGO - MANUAL SEG:') !!}
                                                            </div>
                                                            <div class="col-md-12">
                                                                {!! Form::text('numeroPadraoMSEG', $numeroPadraoMSEG, ['class' => 'form-control input-validation']) !!}
                                                            </div>
                                                            <div class="col-md-offset-8 pull-right col-md-4">
                                                                <button type="submit" class="btn btn-block btn-outline-success mt-2">Salvar</button>
                                                            </div>
                                                        </div>
                                                        {!! Form::close() !!}
                                                    </div>


                                                </div>

                                                <!-- Apenas o usuário que já é administrador do setor Processos pode ver essa parte. Aí, caso deseje, pode "repassar" seu cargo a outro usuário -->
                                                @if(Auth::user()->id == $adminSetorQualidade)
                                                    <div class="col-md-6">
                                                        {!! Form::open(['route' => 'configuracoes.save.quality-admin', 'class' => 'form-horizontal', 'id' => 'form-generate-document']) !!}
                                                            <div class="row">
                                                                <div class="col-md-12 mb-4">
                                                                    <div class="alert alert-warning">
                                                                        <span>Por favor, defina o usuário com privilégios do setor <b>Processos</b>. </span>
                                                                        <small><b>Lembre-se:</b> este usuário é o único que poderá ver documentos confidenciais!</small>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-12">
                                                                    <div class="form-group">
                                                                        <div class="col-md-12 control-label font-bold">
                                                                            {!! Form::label('userAdminQuality', 'Administrador - Setor Processos:') !!}
                                                                        </div>
                                                                        <div class="col-md-12">
                                                                            {!! Form::select('userAdminQuality', $usuariosSetorQualidade, $adminSetorQualidade, ['class' => 'form-control  custom-select']) !!}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-offset-8 pull-right col-md-4 mt-2">
                                                                <button type="submit" class="btn btn-block btn-outline-success">Salvar</button>
                                                            </div>
                                                        {!! Form::close() !!}
                                                    </div>
                                                @endif

                                            </div>

                                        </div>
                                    </div> 
                                </div>
                                          

                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- End Page Content -->
            <!-- ============================================================== -->


            <!-- modal para editar setor -->
            <div id="edit-sector-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Alterando informações do setor</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        </div>
                        {!! Form::open(['route' => 'configuracoes.edit.sector', 'class' => 'form-horizontal']) !!}
                            <div class="modal-body">
                                <div class="col-md-12 mb-4">
                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </div>

                                {!! Form::hidden('id_do_setor', '', ['id' => 'id_do_setor']) !!}
                                <div class="form-group">
                                    {!! Form::label('nome_do_setor', 'NOME DO SETOR:') !!}
                                    {!! Form::text('nome_do_setor', null, ['class' => 'form-control', 'id' => 'nome_do_setor']) !!}
                                </div>
                                <div class="form-group">
                                    {!! Form::label('sigla_do_setor', 'SIGLA DO SETOR:') !!}
                                    {!! Form::text('sigla_do_setor', null, ['class' => 'form-control', 'id' => 'sigla_do_setor']) !!}
                                </div>
                                <div class="form-group">
                                    {!! Form::label('descrição_do_setor', 'DESCRIÇÃO DO SETOR:') !!}
                                    {!! Form::textarea('descrição_do_setor', null, ['class' => 'form-control', 'id' => 'descrição_do_setor']) !!}
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>
                                <button type="submit" class="btn btn-success waves-effect waves-light">Salvar</button>
                            </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
            <!-- /.modal para editar setor -->



            <script type="text/javascript" language="javascript">

                // Click para abrir modal de editar setor
                $(document).on("click", ".open-edit-sector-modal", function () {
                    var id = $(this).data('id');
                    var nome = $(this).data('nome');
                    var sigla = $(this).data('sigla');
                    var descricao = $(this).data('desc');
                    $("#id_do_setor").val(id);
                    $("#nome_do_setor").val(nome);
                    $("#sigla_do_setor").val(sigla);
                    $("#descrição_do_setor").val(descricao);
                });


                // Destacando a subtab
                $("a.speed-subtabs").click(function(){
                    $("a.speed-subtabs").each(function(index){
                        $(this).removeClass('speed-subtab-custom');
                    });
                    $(this).addClass('speed-subtab-custom');
                });

                // Click no switch de permissão de elaborador
                $(".switch-elaborador").change(function(evt) {
                    var user_id = $(this).data("user-id");
                    var obj = {'user_id': user_id, 'valor_permissao_elaborador': this.checked, '_token': "{{ csrf_token() }}"};
                    var url = "{{ URL::route('ajax.usuarios.permissaoElaborador') }}";
                    var msg = "";

                    if(this.checked) msg = "Usuário agora possui permissão de elaborador.";
                    else msg = "Permissão de elaborador removida.";

                    $.ajax({  
                        type: "POST",  
                        url: url,  
                        dataTypé: "JSON",
                        data: obj,
                        success: function(data) {
                            console.log(data);
                            showToast('Sucesso!', msg, 'success');
                        }
                    });     
                });

                // Toda vez que o usuário estiver digitando em campos que possuem essa classe
                $('.input-validation').keypress(function(e) {
                    if(this.value.length>= 4)  return false;
                    
                    return !(e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57));
                }).on('paste', function(event) {
                    event.preventDefault();
                });
            </script>

        </div>
        <!-- ============================================================== -->
        <!-- End Container fluid -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Page wrapper -->
    <!-- ============================================================== -->

@endsection