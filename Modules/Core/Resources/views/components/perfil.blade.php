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

                if (array_key_exists("Core", $menus)) {
                    $menus["Geral"] = $menus["Core"];
                    unset($menus["Core"]);
                }

                ksort($menus);
            @endphp
            
            @foreach ($menus as $key => $menu)
                <div class="checkbox">
                    <h5 style="font-size: 20px"> {{$key}} </h5>
                    <div class="">
                        <input type="checkbox"  id="{{$key}}" data-id="{{$key}}"/>
                        <label for="{{$key}}">Marcar <span class="font-weight-bold">todas</span> permissões do módulo {{$key}}.</label>
                    </div>
                </div>
                
                <div class="ml-5">
                    @include(
                        'core::components.perfil-item-permissao',
                        [
                            'menus' => $menu, 
                            'permissoes' => $permissoes ?? [],
                            'modulo' => $key
                        ]
                    )
                </div>
            @endforeach
        </ul>
    </div>
</div>

@section('footer')
    <script>
        $(document).ready(function() {

            $('input[type=checkbox]').on('click', function(){
                let id = $(this).data('id');
    
                
                $('.'+id).each(function(index, value){
                    var check = $("#" + value.id).is(':checked');
	                if (check == false) $("#" + value.id).prop('checked', true);
	                else $("#" + value.id).prop('checked', false);
                });
                
            });
        });
    </script>

@endsection 