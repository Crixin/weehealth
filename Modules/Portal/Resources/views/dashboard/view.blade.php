@extends('layouts.app')



@section('page_title', __('page_titles.portal.dashboard.load'))

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('portal.home') }}"> @lang('page_titles.general.home') </a></li>
    <li class="breadcrumb-item active"> @lang('page_titles.portal.dashboard.load') </li>    
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
            
            <input type="hidden" id="gedUrl" value="{{ $gedUrl }}" />
            <input type="hidden" id="gedUserToken" value="{{ $gedUserToken }}" />

            <div class="form-body">
                <link rel="stylesheet" href="{{ asset('plugins/gridstack/gridstack.min.css') }}" >
                <script src="{{ asset('plugins/gridstack/gridstack.all.js' )}}"></script>
                
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="card" style="height: 100%">
                            <div class="card-body">
                                <div class="grid-stack" data-gs-animate="yes" >
                                    @foreach ($configuracao as $key => $item)
                                        <div data-gs-x="{{$item->x}}"  data-gs-y="{{$item->y}}" data-gs-width="{{$item->width}}" data-gs-height="{{$item->height}}"  data-gs-locked="true" data-gs-no-resize="true" data-gs-no-move="true" data-gs-auto-position="true" class="grid-stack-item ui-draggable ui-resizable ui-resizable-autohide grafico get-size-component-{{ $key+1 }}"><div class="grid-stack-item-content btn  ui-draggable-handle" style="border-color: #26c6da" ></div>
                                            <div class="col-12 card-body " id="bodyGrafico{{$key+1}}" style="height: 100%;width: 100%"><div class="spinner-border text-dark" role="status" style="margin-top:12%;margin-left:45%;margin-right: 45%"><span class="sr-only">Loading...</span></div></div>
                                            <input type="hidden" name="configGrafico{{$key+1}}" id="configGrafico{{$key+1}}" value="{{json_encode($item->config)}}">
                                        </div>
                                    @endforeach
                                </div>
                            </div>    
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--Graficos--> 
<link href="{{ asset('plugins/chartist-js/dist/chartist.min.css') }}" rel="stylesheet">
<link href="{{ asset('plugins/chartist-js/dist/chartist-init.css') }}" rel="stylesheet">
<link href="{{ asset('plugins/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.css') }}" rel="stylesheet">
<script src="{{asset('plugins/chartist-js/dist/chartist.min.js')}}"></script>
<script src="{{asset('plugins/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.min.js')}}"></script>

<link href="{{ asset('plugins/c3-master/c3.min.css') }}" rel="stylesheet">
<script src="{{ asset('plugins/d3/d3.min.js') }}"></script>
<script src="{{ asset('plugins/c3-master/c3.min.js') }}"></script>

<script src="{{ asset('plugins/sticky-kit-master/dist/sticky-kit.min.js') }}"></script>
<script src="{{ asset('plugins/sparkline/jquery.sparkline.min.js') }}"></script>             
@endsection

@section('footer')
<script src="{{asset('js/dashboard_speed.js')}}"></script>
<script src="{{asset('js/controller/geraParametrosDashboard.js')}}" type="text/javascript"></script>
<script src="{{asset('js/controller/consultaGed.js')}}" type="text/javascript"></script>  
<script src="{{asset('js/controller/datasDashboard.js')}}" type="text/javascript"></script>
<script src="{{asset('js/controller/loadDashboard.js')}}" type="text/javascript"></script>
@endsection