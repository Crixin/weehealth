<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon.png') }}">
    <title>{{ env('APP_NAME') }} - Download Dossiê</title>

    <!-- Bootstrap Core CSS -->
    <link href="{{ asset('plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    <!-- You can change the theme colors from here -->
    <link href="{{ asset('css/colors/green.css') }}" id="theme" rel="stylesheet">

</head>

<body>
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <section id="wrapper">
        <div class="login-register" style="background-image:url({{ asset('images/background/login.jpg') }});">        
            <div class="login-box card">
                <div class="card-body">     
                    @if(Session::has('message'))
                        @component('components.alert') @endcomponent
                        {{ Session::forget('message') }}
                    @endif
                    <form class="form-horizontal form-material" method="POST" action="{{ route('portal.download.dossie.download') }}">
                        {{ csrf_field() }}
                        <input type="hidden" name='token' value="{{$token}}">
                        <h3 class="box-title m-b-20">Autenticação</h3>
                        
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <div class="col-xs-12">
                                <input class="form-control" type="text" name="email" placeholder="Digite o e-mail para autenticação" required {{ $disabled ? "disabled" : ""}}> 
                            </div>
                        </div>

                        <div class="form-group text-center m-t-20">
                            <div class="col-xs-12">
                                <button class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light" {{ $disabled ? "disabled" : ""}} type="submit">@lang('buttons.general.download')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
    </section>

    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>

    <!-- Bootstrap tether Core JavaScript -->
    <script src="{{ asset('plugins/bootstrap/js/popper.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.min.js') }}"></script>

    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="{{ asset('js/jquery.slimscroll.js') }}"></script>

    <!--Wave Effects -->
    <script src="{{ asset('js/waves.js') }}"></script>


    <!--stickey kit -->
    <script src="{{ asset('plugins/sticky-kit-master/dist/sticky-kit.min.js') }}"></script>
    <script src="{{ asset('plugins/sparkline/jquery.sparkline.min.js') }}"></script>

    <!--Custom JavaScript -->
    <script src="{{ asset('js/custom.min.js') }}"></script>

    <!-- ============================================================== -->
    <!-- Style switcher -->
    <!-- ============================================================== -->
    <script src="{{ asset('plugins/styleswitcher/jQuery.style.switcher.js') }}"></script>
</body>

</html>