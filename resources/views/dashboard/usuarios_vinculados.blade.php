@extends('app')


@section('page_title', __('page_titles.dashboard.linked_users'))


@section('breadcrumbs')

    <li class="breadcrumb-item"><a href="{{ route('home') }}"> @lang('page_titles.general.home') </a></li>
    <li class="breadcrumb-item"><a href="{{ route('dashboards') }}"> @lang('page_titles.dashboard.index') </a></li>
    <li class="breadcrumb-item active"> @lang('page_titles.dashboard.linked_users') </li>    

@endsection

@section('content')

    <div class="col-12">
        <div class="card">
            <div class="card-body">
                @if(Session::has('message'))
                    @component('componentes.alert')
                    @endcomponent
                    {{ Session::forget('message') }}
                @endif
                <form method="POST" action="{{ route('dashboards.vincularUsuarios') }}">
                    {{ csrf_field() }}
                    <input type="hidden" name="idDashboard" value="{{ $dashboard->id }}">
                    
                    <div class="form-body">
                        
                        {{-- Parte 1: cadastro --}}
                        <h3 class="box-title m-t-40">  @lang('page_titles.dashboard.linked_users_to') <span style="font-weight: bold;">{{ $dashboard->nome }}</span> <small></small> </h3>
                        <!--<h3 class="box-title"> @lang('page_titles.dashboard.users_available') </h3>-->
                        <hr class="m-t-0 m-b-10">
                        <div class="row p-t-20">
                            <div class="col-md-12 m-b-30">
                                <select multiple id="usuarios_dashboard" name="usuarios_dashboard[]">
                                    @foreach ($usuarios as $usuario)
                                        <option value="{{ $usuario->id }}" {{ in_array($usuario->id, $usuariosVinculados) ? "selected": ""}} >{{ $usuario->name }}</option>
                                    @endforeach 
                                </select>
                                <div class="button-box m-t-20"> <a id="select-all" class="btn btn-danger" href="#"> @lang('buttons.general.select_all') </a> <a id="deselect-all" class="btn btn-info" href="#"> @lang('buttons.general.deselect_all') </a> </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> @lang('buttons.general.save')</button>
                            <a href="{{ route('dashboards') }}" class="btn btn-inverse"> @lang('buttons.general.back')</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
@endsection

@section('footer')
    {{-- MultiSelect --}}
    <link href="{{ asset('plugins/multiselect/css/multi-select.css') }}" rel="stylesheet" type="text/css" />
    <script src="{{ asset('plugins/multiselect/js/jquery.multi-select.js') }}" type="text/javascript" ></script>
    <script src="{{ asset('plugins/quicksearch/jquery.quicksearch.js') }}" type="text/javascript" ></script>

    <script>
        $('#usuarios_dashboard').multiSelect({ 
            keepOrder: true,
            selectableHeader: "<input type='text' class='form-control search-input' autocomplete='off' placeholder='Pesquisar usuários do sistema'>",
            selectionHeader: "<input type='text' class='form-control search-input' autocomplete='off' placeholder='Pesquisar usuários já vinculados ao dashboard'>",
            afterInit: function(ms){
                var that = this,
                    $selectableSearch = that.$selectableUl.prev(),
                    $selectionSearch = that.$selectionUl.prev(),
                    selectableSearchString = '#'+that.$container.attr('id')+' .ms-elem-selectable:not(.ms-selected)',
                    selectionSearchString = '#'+that.$container.attr('id')+' .ms-elem-selection.ms-selected';

                that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
                .on('keydown', function(e){
                if (e.which === 40){
                    that.$selectableUl.focus();
                    return false;
                }
                });

                that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
                .on('keydown', function(e){
                if (e.which == 40){
                    that.$selectionUl.focus();
                    return false;
                }
                });
            },
            afterSelect: function(){
                this.qs1.cache();
                this.qs2.cache();
            },
            afterDeselect: function(values){
                this.qs1.cache();
                this.qs2.cache();
            }
        });

        $('#select-all').click(function() {
            $('#usuarios_dashboard').multiSelect('select_all');
            return false;
        });
        $('#deselect-all').click(function() {
            $('#usuarios_dashboard').multiSelect('deselect_all');
            return false;
        });

        // Removendo a classe que tornava o multiselect tamanho único (e permitindo que ocupe a tela / width inteira)
        $("#ms-usuarios_dashboard").css("width", "auto");
    </script>
@endsection