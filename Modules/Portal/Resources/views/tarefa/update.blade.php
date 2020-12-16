@extends('layouts.app')




@section('page_title', __('page_titles.portal.tarefa.update'))


@section('breadcrumbs')

    <li class="breadcrumb-item"><a href="{{ route('portal.home') }}"> @lang('page_titles.general.home') </a></li>
    <li class="breadcrumb-item"><a href="{{ route('portal.tarefa') }}"> @lang('page_titles.portal.tarefa.index') </a></li>
    <li class="breadcrumb-item active"> @lang('page_titles.portal.tarefa.update') </li>    

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

                <form method="POST" id="formTarefa" action="{{ route('portal.tarefa.alterar') }}">
                    {{ csrf_field() }}
                <input type="hidden" name="idTarefa" value="{{ $tarefa->id }}">
                    
                    <div class="form-body">
                        <div class="row p-t-20">
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('configuracao') ? ' has-error' : '' }}">
                                    <label class="control-label">Configuração</label>
                                    <select class="form-control selectpicker" name="configuracao" id="configuracao" required autofocus>
                                        <option value="" >Selecione</option>
                                        @foreach ($configuracoes as $configuracao)
                                            <option value="{{$configuracao->id}}" {{ $tarefa->configuracao_id == $configuracao->id ? 'selected' : '' }} >{{$configuracao->nome}}</option>
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
                                  {{--       <option value="1m" {{"1m" == $tarefa->frequencia ? 'selected' : '' }}> 1 minuto</option>
                                        <option value="2m" {{"2m" == $tarefa->frequencia ? 'selected' : '' }}> 2 minutos</option>
                                        <option value="3m" {{"3m" == $tarefa->frequencia ? 'selected' : '' }}> 3 minutos</option>
                                        <option value="4m" {{"4m" == $tarefa->frequencia ? 'selected' : '' }}> 4 minutos</option>
                                        <option value="5m" {{"5m" == $tarefa->frequencia ? 'selected' : '' }}> 5 minutos</option> --}}
                                        <option value="10m" {{"10m" == $tarefa->frequencia ? 'selected' : '' }}>A cada 10 minutos</option>
                                        <option value="15m" {{"15m" == $tarefa->frequencia ? 'selected' : '' }}>A cada 15 minutos</option>
                                        <option value="30m" {{"30m" == $tarefa->frequencia ? 'selected' : '' }}>A cada 30 minutos</option>
                                        <option value="1h" {{"1h" == $tarefa->frequencia ? 'selected' : '' }}>A cada 1 hora</option>
                                        <option value="2h" {{"2h" == $tarefa->frequencia ? 'selected' : '' }}>A cada 2 horas</option>
                                        <option value="3h" {{"3h" == $tarefa->frequencia ? 'selected' : '' }}>A cada 3 horas</option>
                                        <option value="4h" {{"4h" == $tarefa->frequencia ? 'selected' : '' }}>A cada 4 horas</option>
                                        <option value="6h" {{"6h" == $tarefa->frequencia ? 'selected' : '' }}>A cada 6 horas</option>
                                        <option value="dailyAt" {{"dailyAt" == $tarefa->frequencia ? 'selected' : '' }}> Todo dia às ... </option>
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
                                    <input class="form-control" type="time" name="hora" id="hora" value="{{$tarefa->hora}}" {{"dailyAt" == $tarefa->frequencia ? '' : 'readonly' }} />
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
                                    <input type="text" id="pasta" name="pasta" value="{{ $tarefa->pasta }}" class="form-control" required >
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
                                    <input type="text" id="pastaRejeitados" name="pastaRejeitados" value="{{ $tarefa->pasta_rejeitados }}" class="form-control" required >
                                    <small class="form-control-feedback"> Digite o nome da pasta. </small> 

                                    @if ($errors->has('pastaRejeitados'))
                                        <br/>    
                                        <span class="help-block text-danger">
                                            <strong>{{ $errors->first('pastaRejeitados') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group{{ $errors->has('identificador') ? ' has-error' : '' }}">
                                    <label class="control-label">Carácter Identificador</label>
                                    <input type="text" id="identificador" maxlength="1" name="identificador" value="{{ $tarefa->identificador }}" class="form-control" required >
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
                                    <select  title="Selecione o processo desejado"  data-nome="Área para Consulta"   name="area" id="area" class="selectpicker form-control" data-live-search="true" data-actions-box="true" required="true" >  
                                        @foreach ($empresas as $key => $empresa)
                                            <optgroup  value="{{ $empresa->id }}" label="{{$empresa->nome}}">
                                            @foreach ($empresa->portalProcesses as $key => $processo)
                                                <option  value="{{ $processo['pivot']['id_area_ged'] }}" {{ $processo['pivot']['id_area_ged'] == $tarefa->area ? 'selected' : '' }} > {{ $processo->nome }} </option>
                                            @endforeach
                                        @endforeach
                                    </select>
                                    <small class="form-control-feedback"> Selecione o processo desejado. </small> 
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
                                    <select class="form-control selectpicker" name="tipo_indexacao" id="tipo_indexacao" required >
                                        <option value="">Selecione</option>
                                        <option value="REGISTRO" {{ $tarefa->tipo_indexacao == 'REGISTRO' ? 'selected' : '' }}>Registro</option>
                                        <option value="DOCUMENTO" {{ $tarefa->tipo_indexacao == 'DOCUMENTO' ? 'selected' : '' }}>Documento</option>
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
                            <table class="table" id="table" >
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
                                <tbody id="tableIndices" >
                                    @foreach ($indices as $key => $indice)
                                        <tr data-id="{{$key+1}}" class="indice">
                                            <td>{{$key+1}}</td>
                                            <td><input type="checkbox" {{$indice->selecionado == true ? 'checked' :''}} data-id="{{$key+1}}"  id="selecionado-{{$key+1}}" class="selecionado filled-in chk-col-cyan"/><label for="selecionado-{{$key+1}}">Selecionado</label></td>
                                            <td id="indice{{$key+1}}" data-identificador="{{$indice->identificador}}">{{$indice->nomeIndice}}</td>
                                            <input type="hidden" name="tipoIndice{{$key+1}}" id="tipoIndice{{$key+1}}" value="{{$indice->tipoIndice ?? 0}}">
                                            <td><input  type="checkbox"  {{$indice->indexador ?? '' == true ? 'checked' :''}} id="indexador{{$key+1}}" class="filled-in chk-col-cyan" ><label for="indexador{{$key+1}}">Sim</label></td>
                                            <td><input  type="checkbox" {{$indice->autoupdate == true ? 'checked' :''}} id="autoupdate{{$key+1}}" class="filled-in chk-col-cyan" ><label for="autoupdate{{$key+1}}">Ativo</label></td>
                                            <td id="tdPosicao{{$key+1}}" ><input id="posicao{{$key+1}}" style="display: {{$indice->selecionado == true ? 'block' :'none'}}" type="number" min="0" value="{{$indice->posicao}}" class="form-control"></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="button" class="btn btn-success"> <i class="fa fa-check"></i> @lang('buttons.general.save')</button>
                        <a href="{{ route('portal.tarefa') }}" class="btn btn-inverse"> @lang('buttons.general.back')</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
@endsection

@section('footer')
<script type="text/javascript" src="{{asset('js/controller/tarefa.js')}}"></script>
@endsection 