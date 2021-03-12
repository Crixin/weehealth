<div class="col-md-6">
    <div class="card card-outline-info">
        <div class="card-header">
            <h4 class="m-b-0 text-white">Este documento está em revisão -  Previsão Próxima revisão: <b>{{-- {{ \Carbon\Carbon::createFromFormat('Y-m-d', $validadeDoc)->format('d/m/Y') }} --}}</b>  </h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <p class="card-text">Você pode cancelar a revisão à qualquer momento clicando no botão.</p>
                </div>
                <div class="col-md-12 m-t-20">
                    <button type="button" class="btn btn-block btn-danger" data-toggle="modal" data-target="#confirm-cancel-review-modal" >Cancelar Revisão</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="confirm-cancel-review-modal" role="dialog" aria-labelledby="label-modal-cancelar-revisao" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="label-modal-cancelar-revisao">Cancelar revisão</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
   
            {{ Form::open(['route' => 'docs.documento.cancelar-revisao', 'method' => 'POST']) }}
                <div class="modal-body">
                    {!! Form::hidden('documento_id', $documento) !!}
                    Deseja cancelar a revisão deste documento? <br>(Os vínculos entre documentos e anexos não serão removidos! Caso queira desfazê-los, terá que desfazer manualmente)
                    <div class="row mt-3">
                        <div class="form-group">
                            <div class="col-md-12 control-label font-bold">
                                {!! Form::label('justificativaCancelamentoRevisao', 'JUSTIFICATIVA:') !!}
                            </div>
                            <div class="col-md-12">
                                {!! Form::textarea('justificativaCancelamentoRevisao', null, ['class' => 'form-control', 'required' => 'required']) !!}
                            </div>
                        </div>
                    </div> 
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger waves-effect">Rejeitar</button>
                </div>
            {{ Form::close() }}        
        </div>
    </div>
</div>
