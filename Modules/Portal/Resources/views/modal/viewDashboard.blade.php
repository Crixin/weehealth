<input type="hidden" id="gedUrl" value="{{ $gedUrl }}" />
<input type="hidden" id="gedUserToken" value="{{ $gedUserToken }}" />

<div class="modal fade" id="modalViewDashboard"  tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" >
        <div class="modal-content" >
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">@lang('page_titles.modalViewDashboard.index')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" >
                <link rel="stylesheet" href="{{ asset('plugins/gridstack/gridstack.min.css') }}" >
                <script src="{{ asset('plugins/gridstack/gridstack.all.js' )}}"></script>
                <div class="row">
                    <div class="col-sm-12 col-md-12">
                        <div class="card" style="height: 100%">
                            <div class="card-body">
                                <div class="grid-stack" data-gs-animate="yes" >
                                    
                                </div>
                            </div>    
                        </div>
                    </div>
                </div>
            </div> 
        </div>
    </div>
</div>
<script>
$(document).ready(function(){

    $(document).on('show.bs.modal', '.modal', function () {
        grid.removeAll();
        buscaDashboard()
        .then((config) => {
            var items = GridStack.Utils.sort(config);
            grid.batchUpdate();
            var indexAdd = 1;
            items.forEach(function (node) {
                grid.addWidget("<div class='grid-stack-item-content ui-draggable-handle get-size-component-"+indexAdd+"' data-gs-no-move='yes' data-gs-no-resize='yes' data-gs-locked='yes'><div class='grid-stack-item-content' style='border-color: #26c6da'></div><div class='col-12 card-body' id='bodyGrafico"+indexAdd+"' style='height: 100%;width: 100%'></div><input type='hidden' name='configGrafico"+indexAdd+"' id='configGrafico"+indexAdd+"' value='"+ JSON.stringify(node['config']) +"'></div>", node['x'], node['y'], node['width'], node['height']);

                $('#bodyGrafico'+indexAdd).append('<div class="spinner-border text-dark" role="status" style="margin-top:12%;margin-left:45%;margin-right:45%"><span class="sr-only">Loading...</span></div>');
                verificaGrafico(indexAdd,node['config']);
                indexAdd ++;
            });
            grid.commit(); 

        }).catch(function(error_msg){
            console.log("#### ERROR AO BUSCAR DASHBOARD! ####");
            console.log("ERROR ao buscar dashboard: "+ error_msg);
        });
    });

    function buscaDashboard()
    {
        var id = $('#modalViewDashboard').attr('data-id');
        return new Promise((resolve,reject)=>{
            $.ajax({
                url: '/portal/dashboards/buscaDashboard',
                type: 'POST',
                data: { idDashboard: id},
                dataType: 'JSON',
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    resolve(data);
                },
                error: function(error){
                    reject(error);
                }
            });
        });
    }
})
</script>
<script src="{{asset('js/dashboard_speed.js')}}"></script>
<script src="{{asset('js/controller/geraParametrosDashboard.js')}}" type="text/javascript"></script>
<script src="{{asset('js/controller/consultaGed.js')}}" type="text/javascript"></script> 
<script src="{{asset('js/controller/datasDashboard.js')}}" type="text/javascript"></script>   
<script src="{{asset('js/controller/loadDashboard.js')}}" type="text/javascript"></script> 

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