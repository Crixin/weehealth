<div class="row">
    <div class="col-md-3">
        <div class="control-label font-bold text-center">
            <h3>Pré-visualização do Documento</h3>
            <div class="text-center">

{{--                 <a href="{{ asset('plugins/onlyoffice-php/doceditor.php?fileID=').$docPath.'&type=embedded' }}" class="btn btn-lg btn-success" target="_blank"> Visualizar </a>
 --}}               {{--  <a href="{{ asset('plugins/onlyoffice-php/Storage').'/'. substr($docPath, strrpos($docPath, '/') + 1)  }}" class="btn btn-lg btn-success" target="_blank"> <i class="mdi mdi-cloud-download"></i> Download </a> --}}
            </div>
        </div>
    </div>
    <div class="col-md-9">
{{--         {!! Form::open(['route' => 'documentacao.salva-lista-presenca', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
 --}}        {{ csrf_field() }}

        <div class="card">
            <div class="card-body">
                <h4 class="card-title"> Upload de lista de presença </h4>
                {!! Form::hidden('idDocumento', $documento->id) !!}

                {!! Form::file('doc_uploaded', ['class' => 'dropify', 'id' => 'input-file-now',
                'data-allowed-file-extensions'=>'pdf']) !!}
            </div>
        </div>
        <div class="col-md-12">
            <div class="pull-right">
                {!! Form::submit('Salvar Lista', ['class' => 'btn btn-success']) !!}
            </div>
        </div>

{{--         {!! Form::close() !!}
 --}}    </div>
</div>