@extends('app')


@section('page_title', __('page_titles.tarefa.create'))


@section('breadcrumbs')

    <li class="breadcrumb-item"><a href="{{ route('home') }}"> @lang('page_titles.general.home') </a></li>
    <li class="breadcrumb-item"><a href="{{ route('tarefa') }}"> @lang('page_titles.tarefa.index') </a></li>
    <li class="breadcrumb-item active"> @lang('page_titles.tarefa.create') </li>    

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
                
                <form method="POST" id="formTarefa" action="{{ route('tarefa.salvar') }}">
                    {{ csrf_field() }}
                    <div class="form-body">
                        
                        <div class="row p-t-20">
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('configuracao') ? ' has-error' : '' }}">
                                    <label class="control-label">Configuração</label>
                                    <select class="form-control selectpicker" name="configuracao" id="configuracao" required autofocus>
                                        <option value="" >Selecione</option>
                                        @foreach ($configuracoes as $configuracao)
                                            <option value="{{$configuracao->id}}" {{ old('configuracao') == $configuracao->id ? 'selected' : '' }} >{{$configuracao->nome}}</option>
                                        @endforeach
                                    </select>
                                    <small class="form-control-feedback"> Selecione a configuração. </small> 

                                    @if ($errors->has('configuracao'))
                                        <br/>
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('configuracao') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group{{ $errors->has('frequencia') ? ' has-error' : '' }}">
                                    <label class="control-label">Frequência</label>
                                    <select title="Selecione a área desejada" data-nome="Área para Consulta" name="frequencia" id="frequencia" class="selectpicker form-control" data-live-search="true" data-actions-box="true" required="true" >  
                                  {{--       <option value="1m"> 1 minuto</option>
                                        <option value="2m"> 2 minutos</option>
                                        <option value="3m"> 3 minutos</option>
                                        <option value="4m"> 4 minutos</option>
                                        <option value="5m"> 5 minutos</option> --}}
                                        <option value="10m">A cada 10 minutos</option>
                                        <option value="15m">A cada 15 minutos</option>
                                        <option value="30m">A cada 30 minutos</option>
                                        <option value="1h">A cada 1 hora</option>
                                        <option value="2h">A cada 2 horas</option>
                                        <option value="3h">A cada 3 horas</option>
                                        <option value="4h">A cada 4 horas</option>
                                        <option value="6h">A cada 6 horas</option>
                                        <option value="dailyAt"> Todo dia às ... </option>
                                    </select>
                                    <small class="form-control-feedback"> Digite com que frequência a tarefa deve executar. </small> 
                                    @if ($errors->has('frequencia'))
                                        <br/>    
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('frequencia') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group{{ $errors->has('hora') ? ' has-error' : '' }}">
                                    <label class="control-label">Hora</label>
                                    <input class="form-control" type="time" name="hora" id="hora" readonly/>
                                    <small class="form-control-feedback"> Digite a hora que frequência da tarefa deve executar. </small> 
                                    @if ($errors->has('hora'))
                                        <br/>    
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('hora') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('pasta') ? ' has-error' : '' }}">
                                    <label class="control-label">Pasta</label>
                                    <input type="text" id="pasta" name="pasta" value="{{ old('pasta') }}" class="form-control" required >
                                    <small class="form-control-feedback"> Digite o nome da pasta. </small> 

                                    @if ($errors->has('pasta'))
                                        <br/>    
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('pasta') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('pasta') ? ' has-error' : '' }}">
                                    <label class="control-label">Pasta de Rejeitados</label>
                                    <input type="text" id="pastaRejeitados" name="pastaRejeitados" value="{{ old('pastaRejeitados') }}" class="form-control" required >
                                    <small class="form-control-feedback"> Digite o nome da pasta de rejeitados. </small> 

                                    @if ($errors->has('pastaRejeitados'))
                                        <br/>    
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('pastaRejeitados') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
 
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('identificador') ? ' has-error' : '' }}">
                                    <label class="control-label">Carácter Identificador</label>
                                    <input type="text" id="identificador" maxlength="1" name="identificador" value="{{ old('identificador') }}" class="form-control" required >
                                    <small class="form-control-feedback"> Digite o carácter identificador. </small> 

                                    @if ($errors->has('identificador'))
                                        <br/>    
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('identificador') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('area') ? ' has-error' : '' }}">
                                    <label class="control-label">Processo</label>
                                    <select  title="Selecione a área desejada"  data-nome="Área para Consulta" name="area" id="area" class="selectpicker form-control" data-live-search="true" data-actions-box="true" required="true" >  
                                        @foreach ($empresas as $key => $empresa)
                                            <optgroup  value="{{ $empresa->id }}" label="{{$empresa->nome}}">
                                            @foreach ($empresa->processes as $key => $processo)
                                                <option  value="{{ $processo['pivot']['id_area_ged'] }}"  {{ old('area') == $processo['pivot']['id_area_ged'] ? 'selected' : '' }}> {{ $processo->nome }} </option>
                                            @endforeach
                                        @endforeach
                                    </select>
                                    <small class="form-control-feedback"> Selecione a área desejada. </small> 
                                    @if ($errors->has('area'))
                                        <br/>    
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('area') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('tipo_indexacao') ? ' has-error' : '' }}">
                                    <label class="control-label">Tipo de Indexação</label>
                                    <select class="form-control selectpicker" name="tipo_indexacao" id="tipo_indexacao" required disabled>
                                        <option value="">Selecione</option>
                                        <option value="REGISTRO" {{ old('tipo_indexacao') == 'REGISTRO' ? 'selected' : '' }}>Registro</option>
                                        <option value="DOCUMENTO" {{ old('tipo_indexacao') == 'DOCUMENTO' ? 'selected' : '' }}>Documento</option>
                                    </select>
                                    <small class="form-control-feedback"> Selecione o tipo de indexação. </small> 

                                    @if ($errors->has('tipo_indexacao'))
                                        <br/>    
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('tipo_indexacao') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <input type="hidden" name="indices" id="indices">
                            <table class="table" id="table" style="display: none">
                                <thead class="thead-dark">
                                    <tr>
                                      <th scope="col">#</th>
                                      <th scope="col">Índice</th>                              
                                      <th scope="col">Nome</th>
                                      <th scope="col">Indexador</th>
                                      <th scope="col">Autoupdate</th> 
                                      <th scope="col">Posição</th>                    
                                    </tr>
                                </thead>
                                <tbody id="tableIndices" ></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="button" class="btn btn-success"> <i class="fa fa-check"></i> @lang('buttons.general.save')</button>
                        <a href="{{ route('tarefa') }}" class="btn btn-inverse"> @lang('buttons.general.back')</a>
                    </div>
                </form>
            
            </div>
        </div>
    </div>
    
@endsection

@section('footer')
<script type="text/javascript" src="{{asset('js/controller/tarefa.js')}}"></script>
@endsection 