@foreach ($menus as $key => $menu)
    @if ($menu->filhos_menu ?? false)
        @if (count(array_intersect($menu->permissao_menu, Auth::user()->corePerfil->permissoes)))
            <li>
                <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="{{ $menu->icone}}"></i><span class="hide-menu">{{$menu->descricao}}</span></a>
                <ul aria-expanded="false" class="collapse {{ $menu->class ?? '' }}">  
                    @include('layouts/itens-menu', ['menus' => $menu->filhos_menu])
                </ul>
            </li>
        @endif
    @else
        @if (count(array_intersect($menu->permissao_menu, Auth::user()->corePerfil->permissoes)))
            <li>
                <a class="waves-effect waves-dark" href="{{ ($menu->route) ? route($menu->route) : '#' }}" aria-expanded="false"> <i class="{{ $menu->icone }}"></i> <span>{{$menu->descricao}}</span> </a>
            </li>  
        @endif
    @endif
@endforeach
