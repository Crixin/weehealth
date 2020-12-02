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
                        <a class="has-arrow waves-effect waves-dark" href="#2" aria-expanded="false"><i class="mdi mdi-plus-circle-outline"></i><span class="hide-menu"> @lang('sidebar_and_header.portal.uls_li_system.register.collapse') </span></a>
                        <ul aria-expanded="false" class="collapse">
                            
                                <li><a href="{{ route('core.empresa') }}"> @lang('sidebar_and_header.portal.uls_li_system.register.item1') </a></li>
                            

                            @if (Auth::user()->administrador)
                                <li><a href="{{ route('core.usuario') }}"> @lang('sidebar_and_header.portal.uls_li_system.register.item4') </a></li>
                                <li><a href="{{ route('core.perfil') }}"> @lang('sidebar_and_header.portal.uls_li_system.register.item6') </a></li>
                                <li><a href="{{ route('core.grupo') }}"> @lang('sidebar_and_header.portal.uls_li_system.register.item2') </a></li>
                            @endif
                            {{-- <li><a href="{{ route('tarefa') }}"> @lang('sidebar_and_header.portal.uls_li_system.register.item7') </a></li> --}}
                        </ul>
                    </li>
                
                    <li>
                        <a class="has-arrow waves-effect waves-dark" href="#3" aria-expanded="false"><i class="mdi mdi-settings"></i><span class="hide-menu"> @lang('sidebar_and_header.portal.uls_li_system.configs.collapse') </span></a>
                        <ul aria-expanded="false" class="collapse">
                            @if ( in_array(Auth::user()->id, Constants::$ARR_SUPER_ADMINISTRATORS_ID) )
                                <li><a href="{{ route('core.configuracao.administradores') }}"> @lang('sidebar_and_header.portal.uls_li_system.configs.item1') </a></li>
                                <li><a href="{{ route('core.configuracao.parametros') }}"> @lang('sidebar_and_header.portal.uls_li_system.configs.item2') </a></li>
                            @endif
                        
                            @if (in_array('conf_setup', $permissaoMenu))
                                <li><a href="{{ route('core.configuracao.setup.index') }}"> @lang('sidebar_and_header.portal.uls_li_system.configs.item3') </a></li>
                            @endif
                        
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
        <a href="{{ route('core.home') }}" class="link" data-toggle="tooltip" title="@lang('sidebar_and_header.tooltip_home')"><i class="mdi mdi-home"></i></a>
        <!-- item-->
        <a href="{{ route('core.configuracao.parametros') }}" class="link" data-toggle="tooltip" title="@lang('sidebar_and_header.portal.uls_li_system.configs.item2')"><i class="ti-settings"></i></a>
        <!-- item-->
        <a href="{{ route('logout') }}" class="link" data-toggle="tooltip" title="@lang('sidebar_and_header.tooltip_logout')"><i class="mdi mdi-power"></i></a>
    </div>
    <!-- End Bottom points-->
@endsection