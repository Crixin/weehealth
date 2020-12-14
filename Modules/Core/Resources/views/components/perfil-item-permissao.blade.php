@foreach ($menus as $key => $menu)
    @if ($menu->filhos_perfil ?? false)
        <li>
            <label> <i class="{{ $menu->icone}}"></i> {{ $menu->descricao }} </label>
            <ul>
                @include('core::components.perfil-item-permissao', ['menus' => $menu->filhos_perfil])
            </ul>
        </li>
    @else
        <li>
            <input type="checkbox" name="{{ $menu->name}}" id="{{ $menu->name}}" />
            <label for="{{ $menu->name}}"> <i class="{{ $menu->icone}}"></i> {{ $menu->descricao }}</label>
        </li>
    @endif
@endforeach