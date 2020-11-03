<div class="modal fade" id="modalConfigDashboard" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">@lang('page_titles.modalDashboard.index')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST"  name="configDashboard" id="configDashboard" >
                <input type="hidden" name="config" id="config">
                <input type="hidden" name="idProcesso" id="idProcesso">
                <input type="hidden" name="idEmpresa" id="idEmpresa">
                <div class="container-fluid">
                        <div class="row" >
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Título do Gráfico</label>
                                    <div class="input-group">
                                        <input type="text" data-nome="Título do Grafico" name="tituloGrafico" id="tituloGrafico" class="form-control" required="true">
                                        {{-- <span class="input-group-addon-required"><font size="4" color="red">*</font></span> --}}
                                    </div>    
                                    <small class="form-control-feedback"> Digite o título do gráfico. </small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Sub-Título do Gráfico</label>
                                    <div class="input-group">
                                        <input type="text" name="subTituloGrafico" id="subTituloGrafico" class="form-control" >
                                    </div>    
                                    <small class="form-control-feedback"> Digite o sub-título do gráfico. </small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Tipo do gráfico</label>
                                    <div class="input-group">
                                        <select  title="Selecione o tipo do gráfico" data-nome="Tipo do gráfico" name="tipoGrafico" id="tipoGrafico" class="selectpicker form-control my-image-selectpicker" required="true">
                                            <option value="">Selecione</option>
                                            <option value="1" data-icon="mdi mdi-chart-line">&nbsp; Gráfico de Linhas</option>
                                            <option value="2" data-icon="mdi  mdi-chart-bar">&nbsp; Gráfico de Barras</option>
                                            <option value="3" data-icon="mdi  mdi-chart-pie">&nbsp; Gráfico de Pizza</option>
                                            <option value="4" data-icon="mdi  mdi-flip-to-front">&nbsp; Totalizador</option>
                                            <option value="customizado" data-icon="mdi mdi-flip-to-front">&nbsp; Customizado</option>
                                        </select>
                                        <!--<span class="input-group-addon-required"><font size="4" color="red">*</font></span>-->
                                    </div>    
                                    <small class="form-control-feedback"> Selecione o tipo de gráfico. </small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Período do Filtro</label>
                                    <div class="input-group">
                                        <select  title="Selecione o período do filtro" data-nome="Período do Filtro" name="periodoGrafico" id="periodoGrafico" class="selectpicker form-control" required="true">
                                            <option value="">Selecione</option>
                                            <option value="1">Dia Atual</option>
                                            <option value="2">Dia Anterior</option>
                                            <option value="3">Semana Anterior</option>
                                            <option value="4">Mês Atual</option>
                                            <option value="5">Mês Anterior</option>
                                            <option value="6">Bimestre</option>
                                            <option value="7">Trimestre</option>
                                            <option value="todos">Todo o período</option>
                                        </select>
                                    </div>
                                    <small class="form-control-feedback"> Selecione o período do filtro. </small> 
                                </div>
                            </div>
                        </div>
                        
                        <div class="row caminho-api hide">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Caminho da API</label>
                                    <div class="input-group">
                                        <input type="text" name="caminhoApi" id="caminhoApi" class="form-control" >
                                    </div>    
                                    <small class="form-control-feedback"> Digite o caminho da API. </small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Tipo de Consulta</label>
                                    <div class="input-group">
                                        <select  title="Selecione o tipo de consulta" data-nome="Tipo de Consulta" name="tipoConsultaGrafico" id="tipoConsultaGrafico" class="selectpicker form-control" data-actions-box="true" required="true">  
                                            <option value="" disabled>Selecione</option>
                                            <option value="1" >Total de Registro por Área</option>
                                            <option value="2" >Total de Registro por Índice e Valor</option>
                                        </select>
                                    </div>
                                    <small class="form-control-feedback"> Selecione o tipo de consulta. </small>                   
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Área para Consulta</label>
                                    <div class="input-group" id="divAreaGrafico">
                                        <select  title="Selecione a área desejada"  data-nome="Área para Consulta" multiple name="areaGrafico[]" id="areaGrafico" class="selectpicker form-control" data-live-search="true" data-actions-box="true" required="true" disabled>  
                                            @foreach ($empresas as $key => $empresa)
                                                <optgroup  value="{{ $empresa->id }}" label="{{$empresa->nome}}">
                                                @foreach ($empresa->processes as $key => $processo)
                                                    <option  value="{{ $processo['pivot']['id_area_ged'] }}" data-idProcesso="{{$processo->id}}" data-idEmpresa="{{$empresa->id}}" data-processo="{{$processo}}" data-empresa="{{$empresa}}"> {{ $processo->nome }} </option>
                                                @endforeach
                                            @endforeach
                                        </select>
                                    </div>
                                    <small class="form-control-feedback"> Selecione a área desejada. </small>                   
                                </div>
                            </div>
                        </div>
                        <div class="row" style="display: none" id="divIndiceValor">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="hidden" name="contadorIndice" id="contadorIndice" value="0">
                                    <label class="control-label">Índice e Valor</label>
                                    <i class="fa fa-plus-circle btn btn-success" id="addIndice" name="addIndice" title="Clique para adicionar um novo índice." style="margin-left:2px;font-size:20px;margin-bottom:5px;display: none"></i>
                                    <div class="input-group">
                                        <table class="table">
                                            <thead class="thead-dark">
                                              <tr>                              
                                                <th scope="col">Índice</th>
                                                <th scope="col">Valor</th>
                                                <th scope="col"></th>                      
                                              </tr>
                                            </thead>
                                            <tbody id="itensTable" name="itensTable"></tbody>
                                        </table>
                                    </div>
                                    <small class="form-control-feedback"> Selecione o(s) índice(s) eo valor </small>  
                                </div>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="save" class="btn btn-success">@lang('buttons.general.save')</button>
                    <button type="button" class="btn btn-inverse" data-dismiss="modal">@lang('buttons.general.cancel')</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="{{asset('js/controller/configDashboard.js')}}"></script>