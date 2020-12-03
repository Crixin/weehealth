

@foreach ($menus as $key => $menu)
    @if ($menu->filhos ?? false)
        <li>
            <a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false"><i class="{{ $menu->icone}}"></i><span class="hide-menu">{{$key}}</span></a>
            <ul aria-expanded="false" class="collapse">  
                @include('layouts/itens-menu', ['menus' => $menu->filhos])
            </ul>
        </li>
    @else
        
        <li>
            <a href="{{ route($menu->route)  }}">{{ $key }} </a>
        </li>  
    @endif
@endforeach
