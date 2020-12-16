@extends('layouts.app')



@section('content')  
    <!-- O que fazer nesta situação? -->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>

	<div class="page-wrapper">
        <div class="container-fluid">

            <div class="row page-titles">
                <div class="col-md-10 col-10 align-self-center">
                    <h3 class="text-themecolor m-b-0 m-t-0">Configurações</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ URL::route('home') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ URL::route('configuracoes') }}">Configurações</a></li>
                        <li class="breadcrumb-item active">Aprovadores de Lista de Presença</li>
                    </ol>
                </div>
                <div class="col-md-7 col-4 align-self-center">
                    <div class="">
                        <button class="right-side-toggle waves-light btn-success btn btn-circle btn-xl pull-right m-l-10   btn-badge badge-top-right" data-count="{{ count(\App\Classes\Helpers::instance()->getNotifications( Auth::user()->id )) }}">
                            <i class="ti-comment-alt text-white"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Start Page Content -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                                
                            <div class="row mt-2 margin-top-1percent">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Nome</th>
                                                <th class="text-center">E-mail</th>
                                                <th class="text-center">Login de Usuário</th>
                                                <th class="text-center text-nowrap">Permissão: aprovar lista de presença</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($usuariosDoSetorCapitalHumano as $usuario)
                                                <tr>
                                                    <td class="text-center"><a href="javascript:void(0)">{{ $usuario->name }}</a></td> 
                                                    <td class="text-center">{{ $usuario->email }}</td>
                                                    <td class="text-center">{{ $usuario->username }}</td>
                                                    <td class="text-center text-nowrap">
                                                        <div class="switch">
                                                            <label>DESABILITADA
                                                                <input type="checkbox" class="switch-aprovador-lista" data-user-id="{{ $usuario->id }}" {{ ($usuario->permissao_aprovar_lista_presenca) ? 'checked' : '' }} ><span class="lever switch-col-light-cyan"></span>HABILITADA
                                                            </label>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!-- End Page Content -->

        </div>
    </div>
@endsection

@section('footer')
<script>
    // Click no switch de permissão de aprovar lista de presença
    $(".switch-aprovador-lista").change(function(evt) {
        var user_id = $(this).data("user-id");
        var obj = {'user_id': user_id, 'valor_permissao_aprovar_lista': this.checked, '_token': "{{ csrf_token() }}"};
        var url = "{{ URL::route('ajax.usuarios.permissaoAprovarListaPresenca') }}";
        var msg = '';

        if(this.checked) msg = 'Usuário agora possui permissão para aprovar listas de presença.';
        else msg = 'Permissão para aprovar listas de presença removida.';

        $.ajax({  
            type: "POST",  
            url: url,  
            dataTypé: "JSON",
            data: obj,
            success: function(data) {
                showToast('Sucesso!', msg, 'success');
            }
        }); 

    });
</script>
@endsection