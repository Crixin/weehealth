@extends('layouts.app')




@section('page_title', __('page_titles.docs.documento.create'))


@section('breadcrumbs')

    <li class="breadcrumb-item"><a href="{{ route('core.home') }}"> @lang('page_titles.general.home') </a></li>
    <li class="breadcrumb-item"><a href="{{ route('docs.documento') }}"> @lang('page_titles.docs.documento.index') </a></li>
    <li class="breadcrumb-item active"> @lang('page_titles.docs.documento.create') </li>    

@endsection



@section('content')

    <div class="col-12">
        <div class="card">
            <div class="card-body">


                @component('components.validation-error', ['errors'])@endcomponent

                @if(Session::has('message'))
                    @component('components.alert')@endcomponent

                    {{ Session::forget('message') }}
                @endif

                <form method="POST" action="{{ route('docs.documento.salvar') }}"> 
                    {{ csrf_field() }}
                    
                    @component(
                        'docs::components.documento', 
                        [
                            'documentoEdit' => [],
                            'tituloDocumento' => '',
                            'codigo' => '',
                            'validade' => '',
                            'setores' => [],
                            'tiposDocumento' => [],
                            'documentosPais' => [],
                            'niveisAcesso' => [],
                            'classificacoes' => [],
                            'documentosVinvulados' => [],
                            'setoresUsuarios' => []
                        ]
                    )
                    @endcomponent
                        
                    <div class="form-actions ">
                        <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> @lang('buttons.general.save')</button>
                        <a href="{{ route('docs.documento') }}" class="btn btn-inverse"> @lang('buttons.general.back')</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
    
@endsection

@section('footer')

<script src="{{ asset('plugins/multiselect/js/jquery.multi-select.js') }}"></script>
<script src="{{ asset('plugins/quicksearch/jquery.quicksearch.js') }}"></script>
<script>
    $(function(){

        /*
        * MultiSelect de NOVO GRUPO DE TREINAMENTO PARA O DOCUMENTO
        */
        $('#optgroup-newGrupoTreinamentoDoc').multiSelect({
            selectableOptgroup: true,
            selectableHeader: "<input type='text' class='form-control search-input' autocomplete='off' placeholder='Pesquisar usuários'>",
            selectionHeader: "<input type='text' class='form-control search-input' autocomplete='off' placeholder='Pesquisar usuários'>",
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

    });
</script>
    
@endsection