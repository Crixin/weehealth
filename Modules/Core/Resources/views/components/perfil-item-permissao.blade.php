@foreach ($menus as $key => $menu)
    @if ($menu->filhos_perfil ?? false)
        <li>
            <label> <i class="{{ $menu->icone}}"></i> {{ $menu->descricao }} </label>
            <ul>
                @include('core::components.perfil-item-permissao', ['menus' => $menu->filhos_perfil, 'permissoes' => $permissoes ?? [] ])
            </ul>
        </li>
    @else
        @php
            $checked =  in_array($menu->name, $permissoes) ? "checked" : "";
        @endphp
        
        <li>
            <input type="checkbox" class="{{$modulo}}" name="{{ $menu->name}}" {{$checked}} id="{{ $menu->name}}" />
            <label for="{{ $menu->name}}"> <i class="{{ $menu->icone}}"></i> {{ $menu->descricao }}</label>
        </li>
    @endif
@endforeach