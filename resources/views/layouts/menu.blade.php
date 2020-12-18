@php 
    $setup = \Modules\Core\Model\Setup::find(1);
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
             
            @php
                // PEGA PELA URL QUAL MODULO O USUARIO ESTA ACESSANDO
                $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                $url = explode('/', str_replace(env("APP_URL"), "", $actual_link))[1];
                $menuJSON = (array) json_decode(file_get_contents(base_path() . '/menu.json'));
                $menus = \Helper::makeMenuPermissions($menuJSON);
                $menuModulo[ucfirst($url)] = $menus[ucfirst($url)];
                
            @endphp
            @each('layouts/itens-menu', (array) $menuModulo, 'menus') 
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
    <a href="{{ route('logout') }}" class="link" data-toggle="tooltip" title="@lang('sidebar_and_header.tooltip_logout')"><i class="mdi mdi-power"></i></a>
</div>
<!-- End Bottom points-->