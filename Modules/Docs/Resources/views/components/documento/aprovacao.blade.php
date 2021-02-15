<div class="row mb-3">
    <div class="col-md-12 row d-flex justify-content-center">
        {{ Form::open(['route' => 'docs.workflow.aprovar', 'method' => 'POST']) }}
            {{ Form::token() }}
            {{ Form::hidden('documento_id', $documento) }}
            {{ Form::hidden('aprovado', "true") }}

            {!! Form::button('Rejeitar <i class="fa fa-remove"></i>', ['class' => 'btn btn-lg btn-danger mr-3', "data-toggle" => "modal", "data-target" => "#modal-justificativa-rejeicao" ] )  !!}
            <button type="submit" class="btn btn-lg btn-success btn-aprovar">Aprovar <i class="fa fa-check"></i></button>
        
        {!! Form::close() !!}

    </div>
</div>

<div class="modal fade" id="modal-justificativa-rejeicao" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Rejeitar Documento</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            {{ Form::open(['route' => 'docs.workflow.aprovar', 'method' => 'POST']) }}
            {{ csrf_field() }}

                {{ Form::hidden('documento_id', $documento) }}
                {{ Form::hidden('aprovado', "false") }}
                <div class="modal-body"> 
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-12 control-label font-bold">
                                {!! Form::label('justificativaRejeicao', 'Justificativa') !!}
                            </div>
                            <div class="col-md-12">
                                {!! Form::textarea('justificativaRejeicao', null, ['class' => 'form-control', 'required' => 'required']) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger waves-effect">Rejeitar</button>
                </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>