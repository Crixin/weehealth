@section('menu')
    @php 
    $setup = \Modules\Core\Model\Setup::find(1); 
    $templete  = $setup->theme_sistema ?? 'weecode';
    $permissaoMenu = [];
    
    @endphp
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <!-- User profile -->
        
        <div class="user-profile" style="background: url({{ ($setup->logo_sistema) ? $setup->logo_sistema : asset('images/background/user-info.jpg') }}) no-repeat;">
            <!-- User profile image -->
            <div class="profile-img"> <img src="{{ (Auth::user()->foto) ? Auth::user()->foto : asset('images/users/user.png') }}"  alt="user" /> </div>
            <!-- User profile text-->
            <div class="profile-text"> <a href="#" class="dropdown-toggle u-dropdown" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true">{{ (!Auth::guest()) ? Auth::user()->name : 'Visitante' }}</a>
                <div class="dropdown-menu animated flipInY">
                    <a href="{{ route('core.usuario.editar', ['id' => Auth::user()->id]) }}" class="dropdown-item"><i class="ti-user"></i> @lang('sidebar_and_header.tooltip_profile')</a>
                    <a href="{{ route('logout') }}" class="dropdown-item"><i class="fa fa-power-off"></i> @lang('sidebar_and_header.tooltip_logout')</a>
                </div>
            </div>
        </div>
        <!-- End User profile text-->

        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <li class="nav-small-cap"> @lang('sidebar_and_header.li_system') </li> 
                
                    <li>
                        <a class="has-arrow waves-effect waves-dark" href="#1" aria-expanded="false"><i class="mdi mdi-chart-areaspline"></i><span class="hide-menu"> @lang('sidebar_and_header.portal.uls_li_system.dashboards.collapse') </span></a> 
                        <ul aria-expanded="false" class="collapse">
                            <li><a href="{{ route('portal.dashboards') }}"> @lang('sidebar_and_header.portal.uls_li_system.dashboards.list') </a></li>
                            <li>
                                <a class="has-arrow" href="#221" aria-expanded="false">@lang('sidebar_and_header.portal.uls_li_system.dashboards.view')</a>
                                <ul aria-expanded="false" class="collapse">
                                    @foreach (Auth::user()->portalDashboards as $key => $userDashboard)
                                        <li><a href="{{ route('portal.dashboard.view', ['id' => $userDashboard->dashboard_id]) }}"> {{$userDashboard->portalDashboard->nome}} </a></li>
                                    @endforeach
                                </ul>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <a class="has-arrow waves-effect waves-dark" href="#2" aria-expanded="false"><i class="mdi mdi-plus-circle-outline"></i><span class="hide-menu"> @lang('sidebar_and_header.portal.uls_li_system.register.collapse') </span></a>
                        <ul aria-expanded="false" class="collapse">
                                <li><a href="{{ route('portal.empresa') }}"> @lang('sidebar_and_header.core.uls_li_system.register.item1') </a></li>
                                <li><a href="{{ route('portal.processo') }}"> @lang('sidebar_and_header.portal.uls_li_system.register.item3') </a></li>

                           
                            
                        </ul>
                    </li>

                    <li>
                        <a class="has-arrow waves-effect waves-dark" href="#3" aria-expanded="false"><i class="mdi mdi-image-filter-none"></i><span class="hide-menu"> @lang('sidebar_and_header.portal.uls_li_system.processes.main') </span></a>
                        <ul aria-expanded="false" class="collapse">  
                            @foreach (Helper::getUserProcesses() as $key => $empresa)
                                @if ( count($empresa->processos) > 0)
                                    <li>
                                        <a class="has-arrow" href="#" aria-expanded="false">{{ $empresa->nome }}</a>
                                        <ul aria-expanded="false" class="collapse">
                                            @foreach ($empresa->processos as $processo)
                                                @if ($processo->nome == Constants::$PROCESSOS[2])
                                                    <li><a href="{{ route('portal.processo.upload', ['idEmpresa' => $empresa->id, 'idProcesso' => $processo->id]) }}"> {{ $processo->nome }} </a></li>
                                                @else
                                                    <li><a href="{{ route('portal.processo.buscar', ['idEmpresa' => $empresa->id, 'idProcesso' => $processo->id]) }}"> {{ $processo->nome }} </a></li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </li>

                    <li>
                        <a class="waves-effect waves-dark" href="{{ route('portal.edicaoDocumento.index') }}" aria-expanded="false"><i class="mdi mdi-grease-pencil"></i><span class="hide-menu"> @lang('sidebar_and_header.portal.uls_li_system.edicaoDocumento.main') </span></a>
                    </li>

                    <li>
                        <a class="has-arrow waves-effect waves-dark" href="#4" aria-expanded="false"><i class="mdi mdi-book-open-page-variant"></i><span class="hide-menu"> @lang('sidebar_and_header.portal.uls_li_system.reports.collapse') </span></a> 
                        <ul aria-expanded="false" class="collapse">
                            <li><a href="{{ route('portal.relatorio.documentos') }}"> @lang('sidebar_and_header.portal.uls_li_system.reports.documents') </a></li>
                        </ul>
                    </li>

                    <li> 
                        <a class="has-arrow waves-effect waves-dark" href="#5" aria-expanded="false"><i class="mdi mdi-file-import"></i><span class="hide-menu"> @lang('sidebar_and_header.portal.uls_li_system.dossie.main') </span></a>
                        <ul aria-expanded="false" class="collapse">
                            <li><a href="{{ route('portal.dossieDocumentos.novo') }}"> @lang('sidebar_and_header.portal.uls_li_system.dossie.generate') </a></li>
                            <li><a href="{{ route('portal.dossieDocumentos.list') }}"> @lang('sidebar_and_header.portal.uls_li_system.dossie.sended') </a></li>
                        </ul>
                    </li>

                    <li> 
                        <a class="has-arrow waves-effect waves-dark" href="#6" aria-expanded="false"><i class="mdi mdi-calendar-clock"></i><span class="hide-menu"> @lang('sidebar_and_header.portal.uls_li_system.tarefa.main') </span></a>
                        <ul aria-expanded="false" class="collapse">
                            <li><a href="{{ route('portal.tarefa') }}"> @lang('sidebar_and_header.portal.uls_li_system.tarefa.sub-main') </a></li>
                            <li><a href="{{ route('portal.config-tarefa') }}"> @lang('sidebar_and_header.portal.uls_li_system.tarefa.config') </a></li>
                        </ul>
                    </li>
                
                <li> 
                    <a class="has-arrow waves-effect waves-dark" href="#70" aria-expanded="false"><i class="mdi mdi-file-import"></i><span class="hide-menu"> @lang('sidebar_and_header.portal.uls_li_system.ged.main') </span></a>
                    <ul aria-expanded="false" class="collapse">
                        <li><a href="{{ route('portal.ged.novo') }}"> @lang('sidebar_and_header.portal.uls_li_system.ged.create') </a></li>
                        <li><a href="{{ route('portal.ged.search-view') }}"> @lang('sidebar_and_header.portal.uls_li_system.ged.edit') </a></li>
                    </ul>
                </li>

            
            
                

            </ul>
        </nav>
        <!-- End Sidebar navigation -->

    </div>
    <!-- End Sidebar scroll-->

    <!-- Bottom points-->
    <div class="sidebar-footer">
        <!-- item-->
        <a href="{{ route('portal.home') }}" class="link" data-toggle="tooltip" title="@lang('sidebar_and_header.tooltip_home')"><i class="mdi mdi-home"></i></a>
        <!-- item-->
        <!--<a href="{{ route('core.configuracao.parametros') }}" class="link" data-toggle="tooltip" title="@lang('sidebar_and_header.portal.uls_li_system.configs.item2')"><i class="ti-settings"></i></a>-->
        <!-- item-->
        <a href="{{ route('logout') }}" class="link" data-toggle="tooltip" title="@lang('sidebar_and_header.tooltip_logout')"><i class="mdi mdi-power"></i></a>
    </div>
    <!-- End Bottom points-->
@endsection