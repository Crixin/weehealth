<div class="form-body">
    <div class="row p-t-20">
        <div class="col-md-12">
            <div class="col-md-6">
                <div class="form-group required{{ $errors->has('nome') ? ' has-error' : '' }}">
                    {!! Form::label('nome', 'Nome', ['class' => 'control-label']) !!}
                    {!! Form::text('nome', $nome ?? "", ['class' => 'form-control', 'required' => 'required']) !!}
                    <small class="text-danger">{{ $errors->first('nome') }}</small>
                </div>
            </div>
        </div>
        <ul>
            @php
                $menus = (array) json_decode(file_get_contents(base_path() . '/menu.json'));
                $menus = array_intersect_key($menus, array_flip($modules));
            @endphp
            @foreach ($menus as $key => $menu)
                <h3> {{$key}} </h3>
                <div class="ml-5">
                    @include('core::components.perfil-item-permissao', ['menus' => $menu])
                </div>
            @endforeach
        </ul>
    </div>
</div>