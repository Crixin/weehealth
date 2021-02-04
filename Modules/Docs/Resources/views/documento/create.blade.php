@extends('layouts.app')




@section('page_title', __('page_titles.docs.documento.create'))


@section('breadcrumbs')

    <li class="breadcrumb-item"><a href="{{ route('core.home') }}"> @lang('page_titles.general.home') </a></li>
    <li class="breadcrumb-item"><a href="{{ route('docs.documento') }}"> @lang('page_titles.docs.documento.index') </a></li>
    <li class="breadcrumb-item active"> @lang('page_titles.docs.documento.create') </li>    

@endsection



@section('content')

    <div class="col-12">
        <div class="card">
            <div class="card-body">


                @component('components.validation-error', ['errors'])@endcomponent

                

                <form method="POST" action="{{route('docs.documento.importar-documento')}}" name="createDocumento" id="createDocumento"> 
                    {{ csrf_field() }}
                    
                    @component(
                        'docs::components.documento', 
                        [
                            'documentoEdit' => [],
                            'tituloDocumento' => '',
                            'codigo' => '',
                            'validade' => '',
                            'setores' => $setores,
                            'tiposDocumento' => $tiposDocumento,
                            'documentosPais' => [],
                            'niveisAcesso' => $niveisAcesso,
                            'classificacoes' => $classificacoes,
                            'documentosVinvulados' => $documentos,
                            'gruposUsuarios' => $gruposUsuarios,
                            'normas' => $normas,
                            'bpmns' => $bpmns,
                            'documentosPaiSelecionados' => [],
                            'documentosVinculadosSelecionados' => [],
                            'normasSelecionados' => [],
                            'grupoTreinamentoSelecionado' => [],
                            'grupoDivulgacaoSelecionado' => []
                        ]
                    )
                    @endcomponent

                    <legend>Ação</legend>
                    <hr>
                    <div class="col-md-12">
                        <div class="radio{{ $errors->has('acao') ? ' has-error' : '' }}">
                        <label for="acao">
                            <div id="radioset">
                                <input  type="radio" id="radio1" name="radio" checked="checked"><label for="radio1">Importar Documento</label>
                                <input  type="radio" id="radio2" name="radio" ><label for="radio2">Criar Documento</label>
                            </div>
                        </label>
                        <small class="text-danger">{{ $errors->first('acao') }}</small>
                        </div>
                    </div>
                    
                        
                    <div class="form-actions ">
                        <button type="submit" class="btn btn-success"> <i class="mdi mdi-chevron-double-right"></i> @lang('buttons.general.next')</button>
                        <a href="{{ route('docs.documento') }}" class="btn btn-inverse"> @lang('buttons.general.back')</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
    
@endsection
