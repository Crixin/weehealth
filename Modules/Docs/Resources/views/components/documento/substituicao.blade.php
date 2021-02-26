<div class="col-md-6">
    <div class="card card-outline-info">
        <div class="card-header">
            <h4 class="m-b-0 text-white">Substituição de documento  </h4>
        </div>
        <div class="card-body">
            <div class="row">
                <p class="card-text">Você pode realizar a substituição do documento atual fazendo <b>upload</b> (o nome será mantido)</b></p>
{{--                 {!! Form::open(['route' => 'documentacao.replace-document', 'method' => 'POST', 'enctype' => 'multipart/form-data', 'style' => 'width: 100%']) !!}
 --}}                    {!! Form::hidden('documento_id_component_substituicao', $documento) !!}

                    {{-- <input type="file" name="new_document" class="dropify m-t-10" data-allowed-file-extensions='{{ $extensoes }}' required/> --}}
                    {!! Form::file('new_document', ['class' => 'dropify', 'required' => 'required', 'accept' => $extensoes ]) !!}

                    <button type="submit" class="btn btn-block btn-success m-t-10">Salvar</button>
{{--                 {!! Form::close() !!} --}}
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>