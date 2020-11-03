<!doctype html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    {{-- Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon.png') }}">
    <title>{{ env('APP_NAME') }} - @yield('page_title')</title>

    <!-- Bootstrap Core CSS -->
    <link href="{{ asset('plugins/bootstrap/css/bootstrap.css') }}" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    @php 
        $setup = \App\Setup::find(1); 
        $templete  = $setup->theme_sistema ?? 'weecode';
        $permissaoMenu = [];
        foreach (Auth::user()->perfil->permissoes ?? [] as $key => $value) {
            $permissaoMenu[] = $value->nome;
        }
    @endphp
    <!-- You can change the theme colors from here -->
    <link href=" {{ asset('css/colors/'.$templete.'.css') }}" id="theme" rel="stylesheet">

    <!-- jquery moment -->
    <script src="{{asset('plugins/moment/moment.js')}}"></script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>


<body class="fix-sidebar fix-header card-no-border">
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
    </div>



    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper">





        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <header class="topbar">
            <nav class="navbar top-navbar navbar-expand-md navbar-light">


                <!-- ============================================================== -->
                <!-- Logo -->
                <!-- ============================================================== -->
                <div class="navbar-header">
                    <a class="navbar-brand" href="{{ route('home') }}">
                        <!-- Logo icon -->
                        <b>
                            <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
                            <!-- Light Logo icon -->
                            <img src="{{ asset('/images/weecode_horizontal.png') }}" alt="homepage" class="light-logo" />
                        </b>
                        <!--End Logo icon -->
                        <!-- Logo text -->
                        <span>
                         <!-- Light Logo text -->    
                         <img src="{{ asset('/images/weecode_horizontal2.png') }}" class="light-logo" alt="homepage" /></span> </a>
                </div>
                <!-- ============================================================== -->
                <!-- End Logo -->
                <!-- ============================================================== -->

                
                <div class="navbar-collapse">
                    <!-- ============================================================== -->
                    <!-- toggle and nav items -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav mr-auto mt-md-0">
                        <!-- This is  -->
                        <li class="nav-item"> <a class="nav-link nav-toggler hidden-md-up text-muted waves-effect waves-dark" href="javascript:void(0)"><i class="mdi mdi-menu"></i></a> </li>
                        <li class="nav-item"> <a class="nav-link sidebartoggler hidden-sm-down text-muted waves-effect waves-dark" href="javascript:void(0)"><i class="ti-menu"></i></a> </li>
                    </ul>
                    <!-- ============================================================== -->
                    <!-- User profile and search -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav my-lg-0">
                        <!-- ============================================================== -->
                        <!-- Comment -->
                        <!-- ============================================================== -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted text-muted waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="mdi mdi-message"></i>
                                <div class="notify"> 
                                    @if (Auth::user()->unreadNotifications->count() > 0)
                                        <span class="heartbit"></span> <span class="point"></span> 
                                    @endif
                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right mailbox scale-up">
                                <ul>
                                    <li>
                                        <div class="drop-title"> @lang('sidebar_and_header.notifications') </div>
                                    </li>
                                    <li>
                                        <div class="message-center">
                                            
                                            @if (Auth::user()->unreadNotifications->count() > 0)
                                                @foreach (Auth::user()->unreadNotifications as $notification)
                                                    <a href="#" style="cursor: default">
                                                        <div class="btn btn-info btn-circle" style="cursor: default"><i class="ti-comment"></i></div>
                                                        <div class="mail-contnet">
                                                            <h5>{{ $notification->data['title'] }}</h5> <span class="mail-desc"> {{ $notification->data['content'] }} </span> <span> {{ date('H:i', strtotime($notification->updated_at)) }} </span> 
                                                        </div>
                                                    </a>
                                                @endforeach                                            
                                            @else
                                                <h5 class="text-center m-t-40">Nenhuma nova notificação.</h5>
                                            @endif

                                        </div>
                                    </li>
                                    <li>
                                        <a class="nav-link text-center" href="{{ route('notificacao') }}"> <strong> @lang('sidebar_and_header.notifications_see_all') </strong> <i class="fa fa-angle-right"></i> </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <!-- ============================================================== -->
                        <!-- End Comment -->
                        <!-- ============================================================== -->
                        <!-- ============================================================== -->
                        <!-- Profile -->
                        <!-- ============================================================== -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="{{ (Auth::user()->foto) ? Auth::user()->foto : asset('images/users/user.png') }}" alt="user" class="profile-pic" /></a>
                            <div class="dropdown-menu dropdown-menu-right scale-up">
                                <ul class="dropdown-user">
                                    <li>
                                        <div class="dw-user-box">
                                            <div class="u-text">
                                                <h4>{{ (!Auth::guest()) ? Auth::user()->name : 'Visitante' }}</h4>
                                                <p class="text-muted">{{ (!Auth::guest()) ? Auth::user()->email : '' }}</p>
                                                
                                                @if ( !in_array(Auth::user()->id, Constants::$ARR_SUPER_ADMINISTRATORS_ID) )
                                                    <a href="{{ route('usuario.editar', ['id' => Auth::user()->id]) }}" class="btn btn-rounded btn-danger btn-sm">@lang('sidebar_and_header.btn_view_profile')</a>
                                                @endif
                                            </div>
                                        </div>
                                    </li>
                                    <li role="separator" class="divider"></li>

                                    @if ( !in_array(Auth::user()->id, Constants::$ARR_SUPER_ADMINISTRATORS_ID) )
                                        <li><a href="{{ route('usuario.editar', ['id' => Auth::user()->id]) }}"><i class="ti-user"></i> @lang('sidebar_and_header.tooltip_profile')</a></li>
                                    @endif
                                    
                                    <li><a href="{{ route('logout') }}"><i class="fa fa-power-off"></i> @lang('sidebar_and_header.tooltip_logout')</a></li>
                                </ul>
                            </div>
                        </li>
                        <!-- ============================================================== -->
                        <!-- Language -->
                        <!-- ============================================================== -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="flag-icon flag-icon-br"></i></a>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <!-- ============================================================== -->
        <!-- End Topbar header -->
        <!-- ============================================================== -->





        <!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <aside class="left-sidebar">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar">
                <!-- User profile -->
                
                <div class="user-profile" style="background: url({{ ($setup->logo_sistema) ? $setup->logo_sistema : asset('images/background/user-info.jpg') }}) no-repeat;">
                    <!-- User profile image -->
                    <div class="profile-img"> <img src="{{ (Auth::user()->foto) ? Auth::user()->foto : asset('images/users/user.png') }}"  alt="user" /> </div>
                    <!-- User profile text-->
                    <div class="profile-text"> <a href="#" class="dropdown-toggle u-dropdown" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="true">{{ (!Auth::guest()) ? Auth::user()->name : 'Visitante' }}</a>
                        <div class="dropdown-menu animated flipInY">
                            <a href="{{ route('usuario.editar', ['id' => Auth::user()->id]) }}" class="dropdown-item"><i class="ti-user"></i> @lang('sidebar_and_header.tooltip_profile')</a>
                            <a href="{{ route('logout') }}" class="dropdown-item"><i class="fa fa-power-off"></i> @lang('sidebar_and_header.tooltip_logout')</a>
                        </div>
                    </div>
                </div>
                <!-- End User profile text-->

                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">
                        <li class="nav-small-cap"> @lang('sidebar_and_header.li_system') </li> 
                        
                        @if (in_array('mod_dashboard', $permissaoMenu))
                            <li>
                                <a class="has-arrow waves-effect waves-dark" href="#1" aria-expanded="false"><i class="mdi mdi-chart-areaspline"></i><span class="hide-menu"> @lang('sidebar_and_header.uls_li_system.dashboards.collapse') </span></a> 
                                <ul aria-expanded="false" class="collapse">
                                    <li><a href="{{ route('dashboards') }}"> @lang('sidebar_and_header.uls_li_system.dashboards.list') </a></li>
                                    <li>
                                        <a class="has-arrow" href="#221" aria-expanded="false">@lang('sidebar_and_header.uls_li_system.dashboards.view')</a>
                                        <ul aria-expanded="false" class="collapse">
                                            @foreach (Auth::user()->dashboards as $key => $userDashboard)
                                                <li><a href="{{ route('dashboard.view', ['id' => $userDashboard->dashboard_id]) }}"> {{$userDashboard->dashboard->nome}} </a></li>
                                            @endforeach
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                        @endif
                        @if (in_array('mod_base', $permissaoMenu) || Auth::user()->administrador)
                            <li>
                                <a class="has-arrow waves-effect waves-dark" href="#2" aria-expanded="false"><i class="mdi mdi-plus-circle-outline"></i><span class="hide-menu"> @lang('sidebar_and_header.uls_li_system.register.collapse') </span></a>
                                <ul aria-expanded="false" class="collapse">
                                    @if (in_array('mod_base', $permissaoMenu))
                                        <li><a href="{{ route('empresa') }}"> @lang('sidebar_and_header.uls_li_system.register.item1') </a></li>
                                        <li><a href="{{ route('grupo') }}"> @lang('sidebar_and_header.uls_li_system.register.item2') </a></li>
                                        <li><a href="{{ route('processo') }}"> @lang('sidebar_and_header.uls_li_system.register.item3') </a></li>
                                    @endif

                                    @if (Auth::user()->administrador)
                                        <li><a href="{{ route('usuario') }}"> @lang('sidebar_and_header.uls_li_system.register.item4') </a></li>
                                        <li><a href="{{ route('perfil') }}"> @lang('sidebar_and_header.uls_li_system.register.item6') </a></li>
                                    @endif
                                    {{-- <li><a href="{{ route('tarefa') }}"> @lang('sidebar_and_header.uls_li_system.register.item7') </a></li> --}}
                                </ul>
                            </li>
                        @endif

                        @if (in_array('ger_processos', $permissaoMenu))
                            <li>
                                <a class="has-arrow waves-effect waves-dark" href="#3" aria-expanded="false"><i class="mdi mdi-image-filter-none"></i><span class="hide-menu"> @lang('sidebar_and_header.uls_li_system.processes.main') </span></a>
                                <ul aria-expanded="false" class="collapse">  
                                    @foreach (Helper::getUserProcesses() as $key => $empresa)
                                        @if ( count($empresa->processos) > 0)
                                            <li>
                                                <a class="has-arrow" href="#" aria-expanded="false">{{ $empresa->nome }}</a>
                                                <ul aria-expanded="false" class="collapse">
                                                    @foreach ($empresa->processos as $processo)
                                                        @if ($processo->nome == Constants::$PROCESSOS[2])
                                                            <li><a href="{{ route('processo.upload', ['idEmpresa' => $empresa->id, 'idProcesso' => $processo->id]) }}"> {{ $processo->nome }} </a></li>
                                                        @else
                                                            <li><a href="{{ route('processo.buscar', ['idEmpresa' => $empresa->id, 'idProcesso' => $processo->id]) }}"> {{ $processo->nome }} </a></li>
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </li>

                            <li>
                                <a class="waves-effect waves-dark" href="{{ route('edicaoDocumento.index') }}" aria-expanded="false"><i class="mdi mdi-grease-pencil"></i><span class="hide-menu"> @lang('sidebar_and_header.uls_li_system.edicaoDocumento.main') </span></a>
                            </li>
                        @endif

                        @if (in_array('mod_relatorios', $permissaoMenu))
                            <li>
                                <a class="has-arrow waves-effect waves-dark" href="#4" aria-expanded="false"><i class="mdi mdi-book-open-page-variant"></i><span class="hide-menu"> @lang('sidebar_and_header.uls_li_system.reports.collapse') </span></a> 
                                <ul aria-expanded="false" class="collapse">
                                    <li><a href="{{ route('relatorio.documentos') }}"> @lang('sidebar_and_header.uls_li_system.reports.documents') </a></li>
                                </ul>
                            </li>
                        @endif

                        @if (in_array('mod_dossie', $permissaoMenu))
                            <li> 
                                <a class="has-arrow waves-effect waves-dark" href="#5" aria-expanded="false"><i class="mdi mdi-file-import"></i><span class="hide-menu"> @lang('sidebar_and_header.uls_li_system.dossie.main') </span></a>
                                <ul aria-expanded="false" class="collapse">
                                    <li><a href="{{ route('dossieDocumentos.novo') }}"> @lang('sidebar_and_header.uls_li_system.dossie.generate') </a></li>
                                    <li><a href="{{ route('dossieDocumentos.list') }}"> @lang('sidebar_and_header.uls_li_system.dossie.sended') </a></li>
                                </ul>
                            </li>
                        @endif

                        @if (in_array('mod_tarefas', $permissaoMenu))
                            <li> 
                                <a class="has-arrow waves-effect waves-dark" href="#6" aria-expanded="false"><i class="mdi mdi-calendar-clock"></i><span class="hide-menu"> @lang('sidebar_and_header.uls_li_system.tarefa.main') </span></a>
                                <ul aria-expanded="false" class="collapse">
                                    <li><a href="{{ route('tarefa') }}"> @lang('sidebar_and_header.uls_li_system.tarefa.sub-main') </a></li>
                                    <li><a href="{{ route('config-tarefa') }}"> @lang('sidebar_and_header.uls_li_system.tarefa.config') </a></li>
                                </ul>
                            </li>
                        @endif
                        
                        <li> 
                            <a class="has-arrow waves-effect waves-dark" href="#70" aria-expanded="false"><i class="mdi mdi-file-import"></i><span class="hide-menu"> @lang('sidebar_and_header.uls_li_system.ged.main') </span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="{{ route('ged.novo') }}"> @lang('sidebar_and_header.uls_li_system.ged.create') </a></li>
                                <li><a href="{{ route('ged.search-view') }}"> @lang('sidebar_and_header.uls_li_system.ged.edit') </a></li>
                            </ul>
                        </li>

                       {{--  <li> <a class="waves-effect waves-dark" href="{{ route('download') }}" aria-expanded="false"><i class="mdi mdi-file-import"></i><span class="hide-menu"> @lang('sidebar_and_header.uls_li_system.downloads.main') </span></a> </li>


                        {{-- <li> <a class="waves-effect waves-dark" href="{{ route('upload.novo') }}" aria-expanded="false"><i class="mdi mdi-upload"></i><span class="hide-menu"> @lang('sidebar_and_header.uls_li_system.upload.main') </span></a> </li> 

                        <li> <a class="waves-effect waves-dark" href="{{ route('logs') }}" aria-expanded="false"><i class="mdi mdi-book-open"></i><span class="hide-menu"> @lang('sidebar_and_header.uls_li_system.logs.main') </span></a> </li> --}}

             {{--            <li>
                            <a class="has-arrow waves-effect waves-dark" href="#5" aria-expanded="false"><i class="mdi mdi-book-open-page-variant"></i><span class="hide-menu"> @lang('sidebar_and_header.uls_li_system.conference.collapse') </span></a>
                            <ul aria-expanded="false" class="collapse">
                                @foreach (Helper::getUserProcesses() as $key => $empresa)
                                    @if ( count($empresa->processos) > 0)
                                        <li>
                                            <a class="has-arrow" href="#" aria-expanded="false">{{ $empresa->nome }}</a>
                                            <ul aria-expanded="false" class="collapse">
                                                @foreach ($empresa->processos as $processo)
                                                    <li><a href="{{ route('relatorio.conferencia', ['idEmpresa' => $empresa->id, 'idProcesso' => $processo->id]) }}"> {{ $processo->nome }} </a></li>
                                                @endforeach
                                            </ul>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </li> --}}
                      
                        @if ( in_array('conf_setup', $permissaoMenu) || in_array(Auth::user()->id, Constants::$ARR_SUPER_ADMINISTRATORS_ID) )
                            <li>
                                <a class="has-arrow waves-effect waves-dark" href="#3" aria-expanded="false"><i class="mdi mdi-settings"></i><span class="hide-menu"> @lang('sidebar_and_header.uls_li_system.configs.collapse') </span></a>
                                <ul aria-expanded="false" class="collapse">
                                    @if ( in_array(Auth::user()->id, Constants::$ARR_SUPER_ADMINISTRATORS_ID) )
                                        <li><a href="{{ route('configuracao.administradores') }}"> @lang('sidebar_and_header.uls_li_system.configs.item1') </a></li>
                                        <li><a href="{{ route('configuracao.parametros') }}"> @lang('sidebar_and_header.uls_li_system.configs.item2') </a></li>
                                    @endif
                                
                                    @if (in_array('conf_setup', $permissaoMenu))
                                        <li><a href="{{ route('configuracao.setup.index') }}"> @lang('sidebar_and_header.uls_li_system.configs.item3') </a></li>
                                    @endif
                                
                                </ul>
                            </li>
                        @endif

                    </ul>
                </nav>
                <!-- End Sidebar navigation -->

            </div>
            <!-- End Sidebar scroll-->
            
            <!-- Bottom points-->
            <div class="sidebar-footer">
                <!-- item-->
                <a href="{{ route('home') }}" class="link" data-toggle="tooltip" title="@lang('sidebar_and_header.tooltip_home')"><i class="mdi mdi-home"></i></a>
                <!-- item-->
                <a href="{{ route('configuracao.parametros') }}" class="link" data-toggle="tooltip" title="@lang('sidebar_and_header.uls_li_system.configs.item2')"><i class="ti-settings"></i></a>
                <!-- item-->
                <a href="{{ route('logout') }}" class="link" data-toggle="tooltip" title="@lang('sidebar_and_header.tooltip_logout')"><i class="mdi mdi-power"></i></a>
            </div>
            <!-- End Bottom points-->

        </aside>
        <!-- ============================================================== -->
        <!-- End Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->






        <!-- ============================================================== -->
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
                    <div class="col-md-12 col-12 align-self-center">
                        <h3 class="text-themecolor m-b-0 m-t-0">  @yield('page_title')  </h3>
                        <ol class="breadcrumb">

                            @yield('breadcrumbs')
                            
                        </ol>
                    </div>
                    @if ( Auth::user()->administrador )
                    <div class="col-md-7 col-12 align-self-center d-none d-md-block">
                        <div class="d-flex mt-2 justify-content-end">
                            <div class>
                                <button class="right-side-toggle waves-effect waves-light   btn btn-circle btn-sm pull-right ml-2 bg-theme">
                                   <i class="ti-settings text-white"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                <!-- ============================================================== -->
                <!-- End Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->


                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->
                
                <!-- Row -->
                <div class="row">

                    @yield('content')

                </div>
                <!-- ============================================================== -->
                <!-- End PAge Content -->
                <!-- ============================================================== -->
                
                
                <!-- ============================================================== -->
                <!-- Right sidebar -->
                <!-- ============================================================== -->
                <!-- .right-sidebar -->
                <div class="right-sidebar shw-rside" style="display: none;">
                    <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 100%;">
                        <div class="slimscrollright" style="overflow: hidden; width: auto; height: 100%;">
                            <div class="rpanel-title"> @lang('sidebar_and_header.service_panel.header') 
                                <span><i class="ti-close right-side-toggle"></i></span>
                            </div>
                            <div class="r-panel-body">
                                {{$templete}}
                                <ul id="themecolors" class="mt-3">
                                    <li><b>@lang('sidebar_and_header.service_panel.item1')</b></li>
                                    <li><a href="javascript:void(0)" data-theme="default" class="default-theme @if($templete == 'default') working @endif">1</a></li>
                                    <li><a href="javascript:void(0)" data-theme="green" class="green-theme @if($templete == 'green') working @endif">2</a></li>
                                    <li><a href="javascript:void(0)" data-theme="red" class="red-theme @if($templete == 'red') working @endif">3</a></li>
                                    <li><a href="javascript:void(0)" data-theme="blue" class="blue-theme @if($templete == 'blue' || $templete == 'weecode') working @endif">4</a></li>
                                    <li><a href="javascript:void(0)" data-theme="purple" class="purple-theme @if($templete == 'purple') working @endif">5</a></li>
                                    <li><a href="javascript:void(0)" data-theme="megna" class="megna-theme @if($templete == 'megna') working @endif">6</a></li>
                                    
                                    <li class="d-block mt-4"><b>@lang('sidebar_and_header.service_panel.item2')</b></li>
                                    <li><a href="javascript:void(0)" data-theme="default-dark" class="default-dark-theme @if($templete == 'default-dark') working @endif" >7</a></li>
                                    <li><a href="javascript:void(0)" data-theme="green-dark" class="green-dark-theme @if($templete == 'green-dark') working @endif">8</a></li>
                                    <li><a href="javascript:void(0)" data-theme="red-dark" class="red-dark-theme @if($templete == 'red-dark') working @endif">9</a></li>
                                    <li><a href="javascript:void(0)" data-theme="blue-dark" class="blue-dark-theme @if($templete == 'blue-dark') working @endif">10</a></li>
                                    <li><a href="javascript:void(0)" data-theme="purple-dark" class="purple-dark-theme @if($templete == 'urple-dark') working @endif">11</a></li>
                                    <li><a href="javascript:void(0)" data-theme="megna-dark" class="megna-dark-theme @if($templete == 'megna-dark') working @endif">12</a></li>      
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                
                <!-- ============================================================== -->
                <!-- End Right sidebar -->
                <!-- ============================================================== -->



            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->


            <!-- ============================================================== -->
            <!-- footer -->
            <!-- ============================================================== -->
            <footer class="footer">
                © {{ date('Y') }} {{ env('APP_NAME') }} por <a href="{{ env('PUBLISHER_WEBSITE') }}" target="_blank">{{ env('APP_PUBLISHER') }}</a> 
            </footer>
            <!-- ============================================================== -->
            <!-- End footer -->
            <!-- ============================================================== -->


        </div>
        <!-- ============================================================== -->
        <!-- End Page wrapper  -->
        <!-- ============================================================== -->





    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->






    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>

    <!-- Bootstrap tether Core JavaScript -->
    <script src="{{ asset('plugins/bootstrap/js/popper.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.min.js') }}"></script>

    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="{{ asset('js/jquery.slimscroll.js') }}"></script>

    <!--Wave Effects -->
    <script src="{{ asset('js/waves.js') }}"></script>

    <!--Menu sidebar -->
    <script src="{{ asset('js/sidebarmenu.js') }}"></script>

    <!--stickey kit -->
    <script src="{{ asset('plugins/sticky-kit-master/dist/sticky-kit.min.js') }}"></script>
    <script src="{{ asset('plugins/sparkline/jquery.sparkline.min.js') }}"></script>

    <!--Custom JavaScript -->
    <script src="{{ asset('js/custom.min.js') }}"></script>

    <!-- SPEED Custom JavaScript -->
    <script src="{{ asset('js/custom_speed.js') }}"></script>
    
    <!-- ============================================================== -->
    <!-- Style switcher -->
    <!-- ============================================================== -->
    <script src="{{ asset('plugins/styleswitcher/jQuery.style.switcher.js') }}"></script>
    
    <!-- jQuery Mask -->
    <script src="{{ asset('plugins/jquery-mask/jquery.mask.min.js') }}"></script>
    <script>
        $(document).ready(function(){

   
/*         $('body').on('DOMNodeInserted', '.cpf', function () {
        }); */
            $('.date').mask('00/00/0000');
            $('.time').mask('00:00:00');
            $('.date_time').mask('00/00/0000 00:00:00');
            $('.cep').mask('00000-000');
            $('.phone').mask('00000-0000');
            $('.phone_with_ddd').mask('(00) 0000-0000');
            $('.phone_us').mask('(000) 000-0000');
            $('.mixed').mask('AAA 000-S0S');
            $('.cpf').mask('000.000.000-00', {reverse: true});
            $('.cnpj').mask('00.000.000/0000-00', {reverse: true});
            $('.money').mask('000.000.000.000.000,00', {reverse: true});
            $('.money2').mask("#.##0,00", {reverse: true});
            $('.ip_address').mask('0ZZ.0ZZ.0ZZ.0ZZ', {
                translation: {
                'Z': {
                    pattern: /[0-9]/, optional: true
                }
                }
            });
            $('.ip_address').mask('099.099.099.099');
            $('.percent').mask('##0,00%', {reverse: true});
            $('.clear-if-not-match').mask("00/00/0000", {clearIfNotMatch: true});
            $('.placeholder').mask("00/00/0000", {placeholder: "__/__/____"});
            $('.fallback').mask("00r00r0000", {
                translation: {
                    'r': {
                    pattern: /[\/]/,
                    fallback: '/'
                    },
                    placeholder: "__/__/____"
                }
                });
            $('.selectonfocus').mask("00/00/0000", {selectOnFocus: true});

        });
    </script>

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert/sweetalert.css') }}">
    <script src="{{ asset('plugins/sweetalert/sweetalert.min.js') }}"></script>

    {{-- toast CSS --}}
    <link href="{{ asset('plugins/toast-master/css/jquery.toast.css') }}" rel="stylesheet">
    <script src="{{ asset('plugins/toast-master/js/jquery.toast.js') }}"></script>
    
    <!-- Select Bootstrap -->
    <link href="{{ asset('plugins/bootstrap-select/bootstrap-select.min.css') }}" rel="stylesheet">
    <script src="{{ asset('plugins/bootstrap-select/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap-select/defaults-pt_BR.js') }}"></script>
   
    <!-- jquery validade -->
    <script src="{{asset('js/jquery.validate.min.js')}}"></script>

    

     
    
    @yield('footer')


    
</body>


</html>
