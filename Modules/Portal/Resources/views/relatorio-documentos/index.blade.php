@extends('core::layouts.app')

@extends('core::layouts.menuPortal')
@yield('menu')


@section('page_title', __('page_titles.report-docs.index'))


@section('breadcrumbs')

<li class="breadcrumb-item"><a href="{{ route('home') }}"> @lang('page_titles.general.home') </a></li>
<li class="breadcrumb-item active"> @lang('page_titles.report-docs.index') </li>

@endsection


@section('content')

<div class="col-12">
    <div class="card">
        <div class="card-body">
            <div class="container">
                <div class="row">
                    <div class="col"></div>
                    <div class="col-10">
                        
                        <h5 class="text-center text-muted m-b-30">@lang('page_titles.warnings.report-docs')</h5>

                        @if(Session::has('message'))
                            @component('core::componentes.alert') @endcomponent
                            {{ Session::forget('message') }}
                        @endif

                        <form method="POST" action="{{ route('relatorio.documentos.gerar') }}">
                            {{ csrf_field() }}

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">CPF</label>
                                                <input type="text" name="cpf" class="form-control  text-center cpf" placeholder="Digite o CPF">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Processos</label>
                                                <select name="processo" id="processo" class="form-control text-center select2" required>
                                                    <option value="">Selecione um processo</option>
                                                    @foreach ($empresas as $key => $empresa)
                                                        <optgroup value="{{ $empresa->id }}" label="{{$empresa->nome}}">
                                                        @foreach ($empresa->processes as $key => $processo)
                                                            <option value="{{ $empresa->id}};{{$processo->id }}"> {{ $processo->nome }} </option>
                                                        @endforeach
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="m-t-20">
                                <button type="submit" class="btn waves-effect waves-light btn-lg btn-block btn-success pull-right">@lang('buttons.general.search') </button>
                            </div>
                        </form>
                    </div>
                    <div class="col"></div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
