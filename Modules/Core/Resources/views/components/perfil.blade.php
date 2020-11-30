<div class="form-body">
    <div class="row p-t-20">
        <div class="col-md-12">
            <div class="col-md-6">
                <div class="form-group required{{ $errors->has('nome') ? ' has-error' : '' }}">
                    {!! Form::label('nome', 'Nome', ['class' => 'control-label']) !!}
                    {!! Form::text('nome', $nome, ['class' => 'form-control', 'required' => 'required']) !!}
                    <small class="text-danger">{{ $errors->first('nome') }}</small>
                </div>
            </div>
        </div>
        <ul>
            <li class="nav-small-cap"> @lang('sidebar_and_header.li_system') </li> 
            @php
                $menu = (array) json_decode(file_get_contents(base_path() . '/menu.json'));
                $keysMenu = array_keys($menu);
                $teste = array_diff_key($menu, array_flip($modules));
                dd($teste);
                $intersect = array_filter(array_flip($menu), function ($m) use ($modules) {
                   
                    return array_intersect($modules, $m);
                });
                

            @endphp

        </ul>
    </div>
</div>