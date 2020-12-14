@extends('layouts.app')



@section('page_title', __('page_titles.portal.dashboard.update'))

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('portal.home') }}"> @lang('page_titles.general.home') </a></li>
    <li class="breadcrumb-item"><a href="{{ route('portal.dashboards') }}"> @lang('page_titles.portal.dashboard.index') </a></li>
    <li class="breadcrumb-item active"> @lang('page_titles.portal.dashboard.update') </li>    
@endsection

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-body">
            @if(Session::has('message'))
                @component('components.alert')
                @endcomponent
                {{ Session::forget('message') }}
            @endif

            <form method="POST" action="{{ route('portal.dashboards.alterar') }}" id="updateDashboard" name="updateDashboard" >
                {{ csrf_field() }}
                <input type="hidden" name="idDashboard" value="{{ $dashboard->id }}">
                <input type="hidden" id="nameDashboard" name="nameDashboard" value="{{ $dashboard->nome }}">
                <input type="hidden" id="saved-data" name="saved-data" value="{{ $dashboard->config }}">
                <input type="hidden" id="idGrafico" name="idGrafico" value="{{ $numGraficos }}">
                <div class="form-body">
                    <link rel="stylesheet" href="{{ asset('plugins/gridstack/gridstack.min.css') }}" >
                    <script src="{{ asset('plugins/gridstack/gridstack.all.js' )}}"></script>
                    
                    <div class="row">
                        <div class="col-md-2 d-none d-md-block">
                            <div id="trash" style="padding: 15px; margin-bottom: 15px;border-color:#fc4b6c" class="text-center btn">
                                <div>
                                    <i class="fa fa-trash" style="font-size: 300%;color: black"></i>
                                </div>
                                <div>
                                <span style="font-size:12px">@lang('page_titles.portal.dashboard.remove')</span>
                                </div>
                            </div>
                            <div class=" btn" style="border-color: #26c6da" >
                                <div class="card-body grid-stack-item-content">
                                    <div>
                                        <i onClick="addWidget('@lang('page_titles.portal.dashboard.config')')" class="fa fa-plus-circle" style="font-size: 250%"></i>
                                    </div>
                                    <div>
                                        <span class="add"  style="font-size:12px">@lang('page_titles.portal.dashboard.add')</span>                       
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-10">
                            <div class="card" style="height: 100%">
                                <div class="card-body">
                                    <div class="grid-stack" data-gs-animate="yes" >                 
                                    </div>
                                </div>    
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-actions" style="margin-top:3%">
                    <button type="button" id="saveDashboard" class="btn btn-success" > <i class="fa fa-check"></i> @lang('buttons.general.save')</button>
                    <a href="{{ route('portal.dashboards') }}" class="btn btn-inverse"> @lang('buttons.general.back')</a>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    let arrayTipoIndiceGED = {!! json_encode($tiposIndicesGED, JSON_HEX_TAG) !!};
</script>
@include('portal::modal/configDashboard', compact('empresas'))
<script src="{{asset('js/controller/dashboard.js')}}"></script>
<script type="text/javascript">
    var idGrafico = Number($('#idGrafico').val());
    montaTela($('#saved-data').val());

    var grid = GridStack.init({
        alwaysShowResizeHandle: /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(
            navigator.userAgent
        ),
        resizable: {
            handles: 'e, se, s, sw, w'
        },
        removable: '#trash',
        removeTimeout: 100,
        acceptWidgets: '.newWidget'
    });

    $('#saveDashboard').on('click',function(){
       save('nameDashboard','updateDashboard');
    });

    function montaTela(inf)
    {
        var valor = JSON.parse(inf);
        for (let index = 0; index < valor.length; index++) {
            var idMonta = index + 1;
            $('.grid-stack').append('<div id="grafico'+idMonta+'"  data-gs-x="'+valor[index].x+'" data-gs-y="'+valor[index].y+'" data-gs-width="'+valor[index].width+'" data-gs-height="'+valor[index].height+'" data-gs-auto-position="true" class="grid-stack-item ui-draggable ui-resizable ui-resizable-autohide"><div class="grid-stack-item-content btn config_grafico ui-draggable-handle" style="border-color: #26c6da" data-id="grafico'+idMonta+'"></div><div><span style="font-size:12px;margin-left:5%">Clique para configurar</span><i class="fa fa-cog" aria-hidden="true" style="margin-left:2%"></i><i class="fa fa-check" style="margin-left:2%"></i></div></div>');
            $('#grafico'+idMonta).val(valor[index].config);
        } 
    }
    
</script>                        
@endsection

@section('footer')
@endsection