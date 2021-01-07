@extends('layouts.app')



@section('page_title', __('page_titles.docs.bpmn.create'))


@section('breadcrumbs')

    <li class="breadcrumb-item"><a href="{{ route('docs.home') }}"> Home </a></li>
    <li class="breadcrumb-item active"> BPMN 2.0 </li>    

    
@endsection

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-body">
            <!-- Start Page Content -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            
							<h3>BPMN 2.0 Modeler</h3>
                            <iframe src="{{ url('plugins/bpmn/index.html') }}" class="resp-iframe"></iframe>

                        </div>
                    </div>
                </div>
            </div>
            <!-- End Page Content -->
        </div>
    </div>
</div>
@endsection