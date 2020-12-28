<div class="form-body">
    <div class="row p-t-20">
        <div class="col-md-12">
            <div class="form-group required{{ $errors->has('documento') ? ' has-error' : '' }}">
                {!! Form::label('documento', 'Documento Externo', ['class' => 'control-label']) !!}<br>
                {!! Form::file('documento', [ 'required', 'accept' =>'.doc, .xls, .DOC, .XLS','class' => 'dropify', 'id' => 'input-file-now']) !!}

                <small class="text-danger">{{ $errors->first('documento') }}</small>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group required{{ $errors->has('setor') ? ' has-error' : '' }}">
                {!! Form::label('setor', 'Setor', ['class' => 'control-label']) !!}
                {!! Form::select('setor',$setores, !empty($documentoExternoEdit) ?  $documentoExternoEdit->setor_id : null, ['id' => 'setor', 'class' => 'form-control selectpicker','data-live-search'=>'true', 'required' => 'required', 'placeholder' => __('components.selectepicker-default')]) !!}
                <small class="text-danger">{{ $errors->first('setor') }}</small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group required{{ $errors->has('fornecedor') ? ' has-error' : '' }}">
            {!! Form::label('fornecedor', 'Fornecedor', ['class' => 'control-label'] ) !!}
            {!! Form::select('fornecedor',$fornecedores, !empty($documentoExternoEdit) ?  $documentoExternoEdit->empresa_id : null, ['id' => 'fornecedor', 'class' => 'form-control', 'required' => 'required',  'placeholder' => __('components.selectepicker-default')]) !!}
            <small class="text-danger">{{ $errors->first('fornecedor') }}</small>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group required{{ $errors->has('versao') ? ' has-error' : '' }}">
                {!! Form::label('versao', 'Versão', ['class' => 'control-label']) !!}
                {!! Form::text('versao', $versao, ['class' => 'form-control versao', 'required' => 'required']) !!}
                
                <small class="text-danger">{{ $errors->first('versao') }}</small>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group required{{ $errors->has('validade') ? ' has-error' : '' }}">
            {!! Form::label('validade', 'Validade', ['class' => 'control-label']) !!}
            {!! Form::date('validade',$validade, ['class' => 'form-control', 'required' => 'required']) !!}
            <small class="text-danger">{{ $errors->first('validade') }}</small>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
            <div class="checkbox{{ $errors->has('lido') ? ' has-error' : '' }}">
                <input type="checkbox" @if ($lido){ checked }@endif name="lido" id="lido" />
                <label for="lido">Eu li e defino esse(s) documento(s) como <span class="font-weight-bold">validado(s)</span>.</label>
            </div>
            <small class="text-danger">{{ $errors->first('lido') }}</small>
            </div>  
        </div>
    </div>
    
</div>

@section('footer')
<link href="{{ asset('plugins/select2/dist/css/select2.min.css') }}" rel="stylesheet" type="text/css">    
<script src="{{ asset('plugins/select2/dist/js/select2.full.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('plugins/jqueryui/jquery-ui.min.js') }}"></script>
<link rel="stylesheet" href="{{ asset('plugins/dropify/dist/css/dropify.min.css') }}">
<script src="{{ asset('plugins/dropify/dist/js/dropify.min.js') }}"></script>
<script>
    $(document).ready(function(){
        // Basic
        $('.dropify').dropify();

        // Translated
        $('.dropify-fr').dropify({
            messages: {
                default: 'Glissez-déposez un fichier ici ou cliquez',
                replace: 'Glissez-déposez un fichier ou cliquez pour remplacer',
                remove: 'Supprimer',
                error: 'Désolé, le fichier trop volumineux'
            }
        });

        // Used events
        var drEvent = $('#input-file-events').dropify();

        drEvent.on('dropify.beforeClear', function(event, element) {
            return confirm("Do you really want to delete \"" + element.file.name + "\" ?");
        });

        drEvent.on('dropify.afterClear', function(event, element) {
            alert('File deleted');
        });

        drEvent.on('dropify.errors', function(event, element) {
            console.log('Has Errors');
        });

        var drDestroy = $('#input-file-to-destroy').dropify();
        drDestroy = drDestroy.data('dropify')
        $('#toggleDropify').on('click', function(e) {
            e.preventDefault();
            if (drDestroy.isDropified()) {
                drDestroy.destroy();
            } else {
                drDestroy.init();
            }
        })
    });
</script>
@endsection