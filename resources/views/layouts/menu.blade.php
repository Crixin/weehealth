@section('menu')
<nav class="sidebar-nav">
    <ul id="sidebarnav">
        <li class="nav-small-cap"> @lang('sidebar_and_header.li_system') </li> 
        @each('layouts/itens-menu', (array) json_decode(file_get_contents(base_path() . '/menu.json')), 'menus') 
    </ul>
</nav>
@endsection