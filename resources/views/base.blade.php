@extends('app')


@section('page_title', __('page_titles.configs.index'))


@section('breadcrumbs')

    <li class="breadcrumb-item"><a href="{{ route('home') }}"> @lang('page_titles.general.home') </a></li>
    <li class="breadcrumb-item active"> @lang('page_titles.configs.index') </li>    

@endsection



@section('content')

<div class="col-12">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title" id="1">1. Title will be here</h4>
            <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem.Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus. Vivamus elementum semper nisi.</p>
            <p> enean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus. Phasellus viverra nulla ut metus varius laoreet. Quisque rutrum. Aenean imperdiet. Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi. Nam eget dui. Etiam rhoncus. Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque sed ipsum. Nam quam nunc, blandit vel, luctus pulvinar,</p>
            <p> enean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus. Phasellus viverra nulla ut metus varius laoreet. Quisque rutrum. Aenean imperdiet. Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi. Nam eget dui. Etiam rhoncus. Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque sed ipsum. Nam quam nunc, blandit vel, luctus pulvinar,</p>
            <p>
                <div class="row">
                    <div class="col-lg-12 col-xlg-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Exemplo do uso de linguagens dinamicamente</h4>
                                <h6 class="card-subtitle">Veja o código e note que existem <code>três formas diferentes</code> de usar os recursos de linguagem.</h6>
                                <div class="button-group">
                                    <button type="button" class="btn btn-lg waves-effect waves-light btn-primary"> {{ trans('action.cancel') }} </button>
                                    <button type="button" class="btn btn-lg waves-effect waves-light btn-secondary"> @lang('action.back') </button>
                                    <button type="button" class="btn btn-lg waves-effect waves-light btn-success"> {{ __('action.create') }} </button>
                                    <button type="button" class="btn btn-lg waves-effect waves-light btn-info"> {{ trans('action.save') }} </button>
                                    <button type="button" class="btn btn-lg waves-effect waves-light btn-warning"> {{ trans('action.edit') }} </button>
                                    <button type="button" class="btn btn-lg waves-effect waves-light btn-danger"> {{ trans('action.delete') }} </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </p>
        </div>
    </div>
</div>
    
@endsection


@section('footer')
    {{-- Coloque aqui todo seu código JavaScript customizado para a página específca --}}
@endsection