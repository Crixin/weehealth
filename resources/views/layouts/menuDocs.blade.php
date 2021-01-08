@section('menu')
    @php 
    $setup = \Modules\Core\Model\Setup::find(1); 
    $templete  = $setup->theme_sistema ?? 'weecode';
    $permissaoMenu = [];
    foreach (Auth::user()->corePerfil->corePermissoes ?? [] as $key => $value) {
        if($value->modulo == 'docs') {
            $permissaoMenu[] = $value->nome;
        }
    }
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
                    <a class="has-arrow waves-effect waves-dark" href="#2" aria-expanded="false"><i class="mdi mdi-plus-circle-outline"></i><span class="hide-menu"> @lang('sidebar_and_header.docs.uls_li_system.register.collapse') </span></a>
                    <ul aria-expanded="false" class="collapse">
                        
                            
                            <li><a href="{{ route('docs.configuracao') }}"> @lang('sidebar_and_header.docs.uls_li_system.register.configuracao') </a></li>
                            <li><a href="{{ route('docs.plano') }}"> @lang('sidebar_and_header.docs.uls_li_system.register.plano') </a></li>
                            <li><a href="{{ route('docs.tipo-documento') }}"> @lang('sidebar_and_header.docs.uls_li_system.register.tipo-documento') </a></li>
                            <li><a href="{{ route('docs.fluxo') }}"> @lang('sidebar_and_header.docs.uls_li_system.register.fluxo') </a></li>
                            <li><a href="{{ route('docs.norma') }}"> @lang('sidebar_and_header.docs.uls_li_system.register.norma') </a></li>
                    </ul>
                </li>

                <li>
                    <a class="has-arrow waves-effect waves-dark" href="#3" aria-expanded="false" ><i class="mdi mdi-format-list-bulleted-type"></i><span class="hide-menu"> @lang('sidebar_and_header.docs.uls_li_system.controle_registro.collapse') </span></a>
                    <ul aria-expanded="false" class="collapse">
                        <li><a href="{{ route('docs.opcao-controle') }}"> @lang('sidebar_and_header.docs.uls_li_system.controle_registro.opcao') </a></li>
                        <li><a href="{{ route('docs.controle-registro') }}"> @lang('sidebar_and_header.docs.uls_li_system.controle_registro.controle') </a></li>
                    </ul>
                </li>
                
                <li>
                    <a class="waves-effect waves-dark" href="{{ route('docs.documento') }}" aria-expanded="false"><i class="mdi mdi-library-books"></i><span class="hide-menu"> @lang('sidebar_and_header.docs.uls_li_system.documento') </span></a>
                </li>

                <li>
                    <a class="waves-effect waves-dark" href="{{ route('docs.documento-externo') }}" aria-expanded="false"><i class="mdi mdi-file-cloud"></i><span class="hide-menu"> @lang('sidebar_and_header.docs.uls_li_system.documento_externo') </span></a>
                </li>
                
                
               

            </ul>
        </nav>
        <!-- End Sidebar navigation -->

    </div>
    <!-- End Sidebar scroll-->

    <!-- Bottom points-->
    <div class="sidebar-footer">
        <!-- item-->
        <a href="{{ route('docs.home') }}" class="link" data-toggle="tooltip" title="@lang('sidebar_and_header.tooltip_home')"><i class="mdi mdi-home"></i></a>
        <!-- item-->
        <!--<a href="{{ route('core.configuracao.parametros') }}" class="link" data-toggle="tooltip" title="@lang('sidebar_and_header.uls_li_system.configs.item2')"><i class="ti-settings"></i></a>-->
        <!-- item-->
        <a href="{{ route('logout') }}" class="link" data-toggle="tooltip" title="@lang('sidebar_and_header.tooltip_logout')"><i class="mdi mdi-power"></i></a>
    </div>
    <!-- End Bottom points-->
@endsection