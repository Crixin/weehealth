@extends('layouts.app')

@extends('layouts.menuCore')
@yield('menu')


@section('page_title', __('page_titles.core.enterprise.update'))


@section('breadcrumbs')

    <li class="breadcrumb-item"><a href="{{ route('core.home') }}"> @lang('page_titles.general.home') </a></li>
    <li class="breadcrumb-item"><a href="{{ route('core.empresa') }}"> @lang('page_titles.portal.enterprise.index') </a></li>
    <li class="breadcrumb-item active"> @lang('page_titles.core.enterprise.update') </li>    

@endsection



@section('content')

    <div class="col-12">
        <div class="card">
            <div class="card-body">


                @if(Session::has('message'))
                    @component('componentes.alert')@endcomponent

                    {{ Session::forget('message') }}
                @endif

                <form method="POST" action="{{ route('core.empresa.alterar') }}">
                    {{ csrf_field() }}
                <input type="hidden" name="idEmpresa" value="{{ $empresa->id }}">
                    
                    <div class="form-body">
                        <div class="row p-t-20">
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('nome') ? ' has-error' : '' }}">
                                    <label class="control-label">Nome</label>
                                    <input type="text" id="nome" name="nome" value="{{ $empresa->nome }}" class="form-control" required autofocus>
                                    <small class="form-control-feedback"> Digite o novo nome da empresa. </small> 

                                    @if ($errors->has('nome'))
                                        <br/>    
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('nome') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('responsavel_contato') ? ' has-error' : '' }}">
                                    <label class="control-label">Responsável para contato</label>
                                    <input type="text" id="responsavel_contato" class="form-control" name="responsavel_contato" value="{{ $empresa->responsavel_contato }}" required>
                                    <small class="form-control-feedback"> Coloque nome, e-mail ou qualquer informação extra que identifique a pessoa que deve ser contatada nessa empresa. </small> 

                                    @if ($errors->has('responsavel_contato'))
                                        <br/>
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('responsavel_contato') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('tipo_empresa') ? ' has-error' : '' }}">
                                    <label class="control-label">Tipo de empresa</label>
                                    <select class="form-control selectpicker" multiple data-live-search="true"  data-actions-box="true" name="tipo_empresa[]" id="tipo_empresa"  required>
                                        @foreach ($tiposEmpresa as $key => $tipo)
                                            <option value="{{$key}}" @if (in_array($key, $tipoSelecionados)) selected  @endif >{{ucfirst(strtolower($tipo))}}</option>    
                                        @endforeach
                                    </select>
                                    @if ($errors->has('tipo_empresa'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('tipo_empresa') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('cnpj') ? ' has-error' : '' }}">
                                    <label class="control-label">CNPJ</label>
                                    <input type="text" id="cnpj" class="form-control cnpj" name="cnpj" value="{{ $empresa->cnpj }}" required>
                                    <small class="form-control-feedback"> Digite apenas números. </small> 

                                    @if ($errors->has('cnpj'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('cnpj') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('telefone') ? ' has-error' : '' }}">
                                    <label class="control-label">Telefone</label>
                                    <input type="text" id="telefone" name="telefone" value="{{ $empresa->telefone }}" class="form-control phone_with_ddd" required>
                                    <small class="form-control-feedback"> Digite apenas números. </small> 

                                    @if ($errors->has('telefone'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('telefone') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('cidade_id') ? ' has-error' : '' }}">
                                    <label class="control-label">Cidade</label>
                                    <select class="form-control selectpicker" data-live-search="true" data-actions-box="true" id="cidade_id" name="cidade_id" value="{{ $empresa->cidade_id }}" required>
                                        @foreach ($cidades as $estado => $cidadesDoEstado)
                                            <optgroup label="{{ $estado }}">
                                                @foreach ($cidadesDoEstado as $key => $value)
                                                    @if ( $key == $empresa->cidade_id )
                                                        <option value="{{ $key }}" selected> {{ $value }} </option>    
                                                    @else
                                                        <option value="{{ $key }}"> {{ $value }} </option>    
                                                    @endif
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>

                                    @if ($errors->has('cidade_id'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('cidade_id') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('pasta_ftp') ? ' has-error' : '' }}">
                                    <label class="control-label">Pasta FTP <small class="text-info">- Digite apenas o diretório (que complemente o caminho exibido à esquerda)</small> </label>
                                    <div class="input-group">
                                        <span class="input-group-addon" id="pasta_ftp">ftp://{{ Helper::getClientFTP() }}</span>
                                        <input type="text" name="pasta_ftp" class="form-control" id="pasta_ftp" value="{{ $empresa->pasta_ftp }}">
                                    </div>
                                    <small class="form-control-feedback"> O valor pode possuir apenas letras, barra ( / ) e <i>underline</i> ( _ ). Além disso, o valor deve acabar com uma barra. </small> 

                                    @if ($errors->has('pasta_ftp'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('pasta_ftp') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('obs') ? ' has-error' : '' }}">
                                    <label class="control-label">Observações</label>
                                    <textarea id="obs" name="obs" class="form-control">{{ $empresa->obs }}</textarea>

                                    @if ($errors->has('obs'))
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('obs') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-success"> <i class="fa fa-check"></i> @lang('buttons.general.save')</button>
                        <a href="{{ route('core.empresa') }}" class="btn btn-inverse"> @lang('buttons.general.back')</a>
                    </div>

                </form>

            </div>
        </div>
    </div>
    
@endsection


@section('footer')

    {{-- Select 2 --}}
    <link href="{{ asset('plugins/select2/dist/css/select2.min.css') }}" rel="stylesheet" type="text/css">    
    <script src="{{ asset('plugins/select2/dist/js/select2.full.min.js') }}" type="text/javascript"></script>
    <script>
        $(".select2").select2();
    </script>

@endsection 